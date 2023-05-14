<?php

namespace App\Services;

use App\Http\Requests\FilterTrackRequest;
use App\Http\Requests\SearchTrackRequest;
use App\Models\File;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Tag;
use App\Models\Track;
use App\Models\UsageType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use getID3;

class TrackService
{
    public function uploadTrackFile($oTrack, $oFile, $sFolder)
    {
        $aTrackFilePath = $this->getPathTrackFile($sFolder, $oTrack->id);
        $oFileTrackDemo = $oTrack->file()->where('type', 'demo')->first();
        $oFileTrackDownload = $oTrack->file()->where('type', 'download')->first();
        $oTrackFiles = ['.wav' => $oFileTrackDownload, '.mp3' => $oFileTrackDemo];
        if (!empty($oFileTrackDemo)){

            foreach ($oTrackFiles as $sTrackFileType => $oTrackFile){
                unlink($oTrackFile->path);
                $oTrackFile->path = $aTrackFilePath['trackFilePath'].$sTrackFileType;
                $oTrackFile->save();
            }

            $oFile->move($aTrackFilePath['trackFolderPath'], $aTrackFilePath['fileName'].'.wav');

            $this->trackConversionWavToMp3($aTrackFilePath);
        }else {
            $oFile->move($aTrackFilePath['trackFolderPath'], $aTrackFilePath['fileName'].'.wav');
            $this->trackConversionWavToMp3($aTrackFilePath);
            $oTrack->file()->createMany([
                [
                    'type' => 'download',
                    'path' => $aTrackFilePath['trackFilePath'].'.wav',
                ],
                [
                    'type' => 'demo',
                    'path' => $aTrackFilePath['trackFilePath'].'.mp3',
                ]
            ]);
        }
        // Получение пиков waveform и сохранение их в бд
        $code = 0;
        $data = [];
        exec('/usr/local/bin/audiowaveform -i '.$aTrackFilePath['trackFilePath'].'.mp3'.' -o '.$aTrackFilePath['trackFolderPath'].'/track_waveform.json --pixels-per-second 10 --bits 8', $data, $code);
        $json = file_get_contents(public_path($aTrackFilePath['trackFolderPath'].'/track_waveform.json'));
        $aData = json_decode($json, true);
        $oTrack->pick = $aData['data'];

        // Получение продолжительности трека и сохранение в бд
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($aTrackFilePath['trackFilePath'].'.mp3');
        $oTrack->duration = gmdate('i:s', $fileInfo['playtime_seconds']);
        $oTrack->save();

        unlink(public_path($aTrackFilePath['trackFolderPath'].'/track_waveform.json'));

    }

    public function trackConversionWavToMp3($aTrackFilePath)
    {
        exec('ffmpeg -i '.$aTrackFilePath['trackFilePath'].'.wav'.' '.$aTrackFilePath['trackFilePath'].'.mp3');
    }
    
    public function getPathTrackFile($sFolder, $oTrackId)
    {
        if (!file_exists(public_path('uploads/'.$sFolder))){
            mkdir(public_path('uploads/'.$sFolder.'/'), 0755, true);
        }

        $sFileName = str_random(10).'_'.time();
        $sFolderName = $sFolder.'_'.$oTrackId;
        $sTrackFolderPath = 'uploads/'.$sFolder.'/'.$sFolderName;
        $sTrackFilePath = 'uploads/'.$sFolder.'/'.$sFolderName.'/'.$sFileName;

        return [
            'fileName' => $sFileName,
            'trackFolderPath' => $sTrackFolderPath,
            'trackFilePath' => $sTrackFilePath,
        ];
    }

    public function getTrackFile(Track $oTrack, $oUser)
    {
        if (!empty($oUser)){
            $oTrackFile = $oTrack->file()->where('type', Track::DOWNLOAD_TYPE)->first();

            if (empty($oTrackFile)){
                $oTrackFile = $oTrack->file()->where('type', Track::DEMO_TYPE)->first();
            }
            $oTrackDownload = $oTrack->download()->where('user_id', $oUser->id)->first();
            if (empty($oTrackDownload)){
                $oTrack->download()->attach($oUser, ['download_at' => Carbon::now()->toDateTimeString()]);
            }
        }else {
            $oTrackFile = $oTrack->file()->where('type', Track::DEMO_TYPE)->first();

            if (empty($oTrackFile)){
                $oTrackFile = $oTrack->file()->where('type', Track::DOWNLOAD_TYPE)->first();
            }
        }
        return $oTrackFile;
    }

    public function downloadTrackFile(Track $oTrack, $oUser = null)
    {

        $oTrackFile = $this->getTrackFile($oTrack, $oUser);
        if (!empty($oUser)){
            $oTrack->last_download_at = Carbon::now()->toDateTimeString();
            $oTrack->save();
        }

        return response()->download(base_path('public/' . $oTrackFile->path));
    }

    public function getDataTrackOrTracks($aDataId = null)
    {
        $aResult = [];
        if (!empty($aDataId) and !is_array($aDataId)){
            $aDataId = [$aDataId];
        }

        $oTracks = Track::query()->when($aDataId, function ($query) use ($aDataId) {
            $query->whereIn('id', $aDataId);
        })->with(['tags', 'moods', 'usage_types', 'genres', 'image'])->get();

        foreach ($oTracks as $oTrack) {
            $aResult[] = [
                'id' => $oTrack->id,
                'cover' => env('BACKEND_URL') . $oTrack->image->path,
                'name' => $oTrack->name,
                'author' => $oTrack->user->name,
                'downloads' => $oTrack->downloaded_count,
                'duration' => $oTrack->duration,
                'pick' => $oTrack->pick,
                'bpm' => $oTrack->temp,
                'genre' => $oTrack->genres->pluck('name')->toArray() ?? '',
                'tags' => $oTrack->tags->pluck('name')->toArray() ?? '',
                'usage_types' => $oTrack->usage_types->pluck('name')->toArray() ?? '',
                'url' => env('BACKEND_URL') . '/api/download/track/' . $oTrack->id ?? '',
                'mood' => $oTrack->moods->pluck('name')->toArray() ?? '',
            ];
        }

        return $aResult;
    }
    
    public function addTrackInFavorite($oTrack, $oUser)
    {
        $oUser->favorites()->syncWithoutDetaching($oTrack);
    }

    public function deleteTrackInFavorite($oTrack, $oUser)
    {
        $oUser->favorites()->detach($oTrack);
    }

    public function getTracksFavoritesUser(User $oUser)
    {
        $aTracksId = $oUser->favorites->map(function ($oTrack) {
            return $oTrack->id;
        });

        return $this->getDataTrackOrTracks($aTracksId->toArray());
    }

    public function getDownloadsTracks(User $oUser)
    {
        $aTracksId = $oUser->downloadsTracks->map(function ($oTrack) {
            return $oTrack->id;
        });

        return $this->getDataTrackOrTracks($aTracksId->toArray());
    }

    public function getGenres()
    {
        return Genre::with('image')->get()->map(function ($oGenre) {
            return [
                'id' => $oGenre->id,
                'name' => $oGenre->name,
                'cover' => $oGenre->image ? env('BACKEND_URL') . $oGenre->image->path : '',
            ];
        });
    }

    public function getMoods()
    {
        return Mood::get(['id', 'name']);
    }

    public function getUsageTypes()
    {
        return UsageType::get(['id', 'name']);
    }

    public function getTags()
    {
        return Tag::get(['id', 'name']);
    }

    public function getFilterTracks(FilterTrackRequest $oRequest)
    {
        $oTracks = Track::query()
            ->when($oRequest->filled(['min_bpm', 'max_bpm']), function ($query) use ($oRequest) {
                $query->whereBetween('temp', [$oRequest->input('min_bpm'), $oRequest->input('max_bpm')])
                ->when($oRequest->filled('track_ids'), function ($subquery) use ($oRequest){
                    $subquery->whereIn('id', $oRequest->input('track_ids'));
                });
            })
            ->when($oRequest->filled('search_value'), function ($query) use ($oRequest) {
                $query->when($oRequest->filled('track_ids'), function ($subsubquery) use ($oRequest) {
                    $subsubquery->whereIn('id', $oRequest->input('track_ids'));
                })->where(function ($subquery) use ($oRequest) {
                    $subquery->WhereHas('moods', function ($subsubquery) use ($oRequest) {
                        $subsubquery->whereIn('name', $oRequest->input('search_value'));
                    })->orWhereHas('genres', function ($subsubquery) use ($oRequest) {
                        $subsubquery->whereIn('name', $oRequest->input('search_value'));
                    })->orWhereHas('usage_types', function ($subsubquery) use ($oRequest) {
                        $subsubquery->whereIn('name', $oRequest->input('search_value'));
                    })->orWhereHas('tags', function ($subsubquery) use ($oRequest) {
                        $subsubquery->whereIn('name', $oRequest->input('search_value'));
                    });
                });
            })
            ->with(['moods', 'genres', 'usage_types', 'tags'])->get();

        foreach ($oTracks as $oTrack){
            $iRating = 0;

            if (!empty($oRequest->input('search_value'))) {
                foreach ($oTrack->{$oRequest->input('search_type')} as $oRelation) {
                    foreach ($oRequest->input('search_value') as $sSearch_value) {
                        if (Str::contains(strtolower($oRelation->name), strtolower($sSearch_value))) {
                            $iRating += Track::EXACT_MATCH_RATION_NOT_STRICTLY;
                        }
                    }
                }

                $iTagMatches = 0;
                foreach (['genres', 'usage_types', 'tags'] as $relation) {
                    foreach ($oTrack->{$relation} as $oRelation) {
                        foreach ($oRequest->input('search_value') as $sSearch_value) {
                            if (Str::contains(strtolower($oRelation->name), strtolower($sSearch_value))) {
                                $iTagMatches += Track::TAG_MATCHING_COEFFICIENT;
                            }
                        }
                    }
                }
                $iRating += min($iTagMatches, Track::MAX_TAG_MATCHING_COEFFICIENT);
            }

            $iPeriod = Track::FORMULA_PERIOD; // Кол-во дней
            $iFullCoefficient = Track::FORMULA_X;
            $daysAfterUpload = max(0, \Illuminate\Support\Carbon::now()->diffInDays($oTrack->created_at));
            $sTimelyCoefficient = $iFullCoefficient - ($iFullCoefficient / $iPeriod) * $daysAfterUpload;
            $iRating += $sTimelyCoefficient;

            $iSalesCoefficient = min(($oTrack->downloaded_count ?: 1) / ($oTrack->listened_count ?: 1), Track::MAX_DOWNLOAD_RATING_VALUE);
            $iRating += $iSalesCoefficient;

            $oTrack->rating = $iRating;
        }

        $perPage = Track::COUNT_PAGINATE_PAGE;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $oTracks->sortByDesc('rating')->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $oPaginatedTracks = new LengthAwarePaginator($currentItems, $oTracks->count(), $perPage, $currentPage);
        $sNextPageUrlTrack = ($oPaginatedTracks->nextPageUrl()) ? env('BACKEND_URL').'/'.request()->path().$oPaginatedTracks->appends(request()->path())->setPath(null)->nextPageUrl() : null;

        $oPaginatedTracks = $oPaginatedTracks->map(function ($oTrack) {
            return [
                'id' => $oTrack->id,
                'cover' => env('BACKEND_URL') . $oTrack->image->path,
                'name' => $oTrack->name,
                'author' => $oTrack->user->name,
                'downloads' => $oTrack->downloaded_count,
                'duration' => $oTrack->duration,
                'pick' => $oTrack->pick,
                'bpm' => $oTrack->temp,
                'tags' => $oTrack->tags->pluck('name')->toArray() ?? '',
                'genre' => $oTrack->genres->pluck('name')->toArray() ?? '',
                'url' => env('BACKEND_URL') . '/api/download/track/' . $oTrack->id ?? '',
                'mood' => $oTrack->moods->pluck('name')->toArray() ?? '',
                'usage_types' => $oTrack->usage_types->pluck('name')->toArray() ?? '',
                'rating' => $oTrack->rating,
            ];
        })->sortByDesc('rating')->all();

        return [
            'tracks' => array_values($oPaginatedTracks),
            'next_page_url' => $sNextPageUrlTrack,
        ];
    }

    public function searchTrack(SearchTrackRequest $oRequest)
    {
        $oTrackRequest = Track::query()->select('tracks.*');
        $oTrackRequest->when($oRequest->filled('search_value'), function ($query) use ($oRequest) {
            $sFullWord = $oRequest->input('search_value');
            $aWords = collect(explode(' ', $sFullWord))->unique();
            $query->where('tracks.name', 'like', '%' . $sFullWord . '%')
                ->orWhereHas('moods', function ($subquery) use ($sFullWord) {
                    $subquery->where('moods.name', 'like', '%' . $sFullWord . '%');
                })
                ->orWhereHas('genres', function ($subquery) use ($sFullWord) {
                    $subquery->where('genres.name', 'like', '%' . $sFullWord . '%');
                })
                ->orWhereHas('usage_types', function ($subquery) use ($sFullWord) {
                    $subquery->where('usage_types.name', 'like', '%' . $sFullWord . '%');
                })
                ->orWhereHas('tags', function ($subquery) use ($sFullWord) {
                    $subquery->where('tags.name', 'like', '%' . $sFullWord . '%');
                })
                ->when(!empty($sFullWord), function ($subquery) use ($aWords) {
                    foreach ($aWords as $aWord) {
                        $subquery->orWhere('tracks.name', 'like', '%' . $aWord . '%')
                            ->orWhereHas('moods', function ($subsubquery) use ($aWord) {
                                $subsubquery->where('moods.name', 'like', '%' . $aWord . '%');
                            })
                            ->orWhereHas('genres', function ($subsubquery) use ($aWord) {
                                $subsubquery->where('genres.name', 'like', '%' . $aWord . '%');
                            })
                            ->orWhereHas('usage_types', function ($subsubquery) use ($aWord) {
                                $subsubquery->where('usage_types.name', 'like', '%' . $aWord . '%');
                            })
                            ->orWhereHas('tags', function ($subsubquery) use ($aWord) {
                                $subsubquery->where('tags.name', 'like', '%' . $aWord . '%');
                            });
                    }
                });
        });

        $aTracksId = $oTrackRequest->pluck('id');

        $oTracks = $oTrackRequest->with(['genres', 'moods', 'usage_types', 'tags'])->paginate(Track::COUNT_PAGINATE_PAGE);

        foreach ($oTracks as $oTrack) {
            $iRating = 0;

            // Коэффициент точного соответствия (работает как LIKE в SQL)
            if (Str::contains(strtolower($oTrack->name), strtolower($oRequest->input('search_value')))) {
                $iRating += Track::EXACT_MATCH_RATION_NOT_STRICTLY;
            }

            // Коэффициент точного соответствия (Строгое соответствие)
            if (strtolower($oTrack->name) === strtolower($oRequest->input('search_value'))) {
                $iRating += Track::EXACT_MATCH_RATION_STRICTLY;
            }

            // Коэффициент совпадения тегов
            $iTagMatches = 0;
            foreach (['moods', 'genres', 'usage_types', 'tags'] as $relation) {
                foreach ($oTrack->{$relation} as $oRelation) {
                    if (Str::contains(strtolower($oRelation->name), strtolower($oRequest->input('search_value')))) {
                        $iTagMatches += Track::TAG_MATCHING_COEFFICIENT;
                    }
                }
            }
            $iRating += min($iTagMatches, Track::MAX_TAG_MATCHING_COEFFICIENT);

            $iPeriod = Track::FORMULA_PERIOD; // Кол-во дней
            $iFullCoefficient = Track::FORMULA_X;
            $daysAfterUpload = max(0, \Illuminate\Support\Carbon::now()->diffInDays($oTrack->created_at));
            $sTimelyCoefficient = $iFullCoefficient - ($iFullCoefficient / $iPeriod) * $daysAfterUpload;
            $iRating += $sTimelyCoefficient;

            $iSalesCoefficient = min(($oTrack->downloaded_count ?: 1) / ($oTrack->listened_count ?: 1), Track::MAX_DOWNLOAD_RATING_VALUE);
            $iRating += $iSalesCoefficient;

            $oTrack->rating = $iRating;
        }

        $sNextPageUrlTrack = ($oTracks->nextPageUrl()) ? env('BACKEND_URL').'/'.request()->path().$oTracks->appends(request()->path())->setPath(null)->nextPageUrl() : null;

        $oTracks = $oTracks->map(function ($oTrack) {
            return [
                'id' => $oTrack->id,
                'cover' => env('BACKEND_URL').$oTrack->image->path,
                'name' => $oTrack->name,
                'author' => $oTrack->user->name,
                'duration' => $oTrack->duration,
                'pick' => $oTrack->pick,
                'url' => env('BACKEND_URL').'/api/download/track/'.$oTrack->id ?? '',
                'rating' => $oTrack->rating,
            ];
        })->sortByDesc('rating')->all();



        return [
            'tracks' => array_values($oTracks),
            'next_page_url' => $sNextPageUrlTrack,
            'tracks_id' => $aTracksId,
        ];

    }

    public function getTrackTop()
    {
        $aTrackIds = DB::table('downloads')->whereBetween('download_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->startOfWeek()])->pluck('track_id')->toArray();
        return array_values(collect($this->getDataTrackOrTracks($aTrackIds))->sortByDesc('downloads')->take(10)->toArray());
    }
}