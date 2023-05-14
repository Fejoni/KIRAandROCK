<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTrackRequest;
use App\Http\Requests\FilterTrackRequest;
use App\Http\Requests\SearchTrackRequest;
use App\Http\Requests\TrackFavoritesRequest;
use App\Models\Licensetemplate;
use App\Models\Track;
use App\Services\LicenseService;
use App\Services\TrackService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    private TrackService $trackService;
    private LicenseService $licenseService;
    private UserService $userService;

    public function __construct(TrackService $oTrackService, LicenseService $oLicenseTrack, UserService $oUserService)
    {
        $this->trackService = $oTrackService;
        $this->licenseService = $oLicenseTrack;
        $this->userService = $oUserService;
    }

    public function getDataTrack($oTrack)
    {
        $aResult = $this->trackService->getDataTrackOrTracks($oTrack);

        return response()->json([
           'success' => true,
           'data' => $aResult
        ]);
    }
    public function getDataTracks(DataTrackRequest $oRequest)
    {
        $aResult = $this->trackService->getDataTrackOrTracks($oRequest->input('track_ids'));

        return response()->json([
            'success' => true,
            'data' => $aResult
        ]);
    }

    public function downloadTrack(Track $oTrack)
    {
        return $this->trackService->downloadTrackFile($oTrack);
    }

    public function downloadTrackFromUser(Request $oRequest, Track $oTrack)
    {
        $oUser = $oRequest->user('api');
        return $this->trackService->downloadTrackFile($oTrack, $oUser);
    }

    public function downloadLicenseForTrack(Request $oRequest, Track $oTrack)
    {
        $oUser = $oRequest->user('api');
        $oAuthorUser = $oTrack->user;
        $sDataLicenseTemplate = $this->licenseService
            ->getDataLicenseForTrack($this->userService
            ->getUser($oUser), $this->userService
            ->getUser($oAuthorUser), $oTrack->toArray());

        $oLicenseTemplate = Licensetemplate::query()->first();

        if ($oLicenseTemplate->path == null){
            $sTrackPath = $this->createorUpdateLicenseForTrack($sDataLicenseTemplate);
            $oLicenseTemplate->path = $sTrackPath;
            $oLicenseTemplate->save();
        }else {
            $this->createorUpdateLicenseForTrack($sDataLicenseTemplate);
        }

        return response()->download(base_path('public/'.$oLicenseTemplate->path));
    }

    public function createorUpdateLicenseForTrack($sDataLicenseTemplate)
    {

        $sLicenseFolderPath = 'uploads/license';

        $sLicenseFileName = 'track_license.txt';
        if (!file_exists($sLicenseFolderPath)){
            mkdir($sLicenseFolderPath.'/', 0755, true);
        }
        $sLicenseFilePath = $sLicenseFolderPath.'/'.$sLicenseFileName;
        $licenseFile = fopen($sLicenseFilePath, 'a+');
        ftruncate($licenseFile, 0);
        fputs($licenseFile, $sDataLicenseTemplate);
        fclose($licenseFile);
        mb_convert_encoding($licenseFile, "UTF-8", "auto");

        return $sLicenseFilePath;
    }

    public function addTrackInFavorite(TrackFavoritesRequest $oRequest): JsonResponse
    {
        $oUser = $oRequest->user('api');
        $oTrack = Track::query()->where('id', $oRequest->input('track'))->get();

        $this->trackService->addTrackInFavorite($oTrack, $oUser);

        return response()->json([
            'success' => true,
        ]);
    }

    public function deleteTrackInFavorite(TrackFavoritesRequest $oRequest)
    {
        $oUser = $oRequest->user('api');
        $oTrack = Track::query()->where('id', $oRequest->input('track'))->get();

        $this->trackService->deleteTrackInFavorite($oTrack, $oUser);

        return response()->json([
            'success' => true,
        ]);
    }

    public function getTracksFavoritesUser(Request $oRequest)
    {
        $oUser = $oRequest->user('api');
        $aFavorites = $this->trackService->getTracksFavoritesUser($oUser);

        return response()->json([
           'success' => true,
           'data' => $aFavorites
        ]);
    }

    public function getIdTracksFavoritesUser(Request $oRequest)
    {
        $oUser = $oRequest->user('api');

        return response()->json([
            'success' => true,
            'data' => $oUser->favorites->pluck('id')
        ]);
    }

    public function getDownloadsTracks(Request $oRequest)
    {
        $oUser = $oRequest->user('api');
        $aDownloadsTracks = $this->trackService->getDownloadsTracks($oUser);

        return response()->json([
           'success' => true,
           'data' => $aDownloadsTracks,
        ]);
    }

    public function getIdDownloadsTracks(Request $oRequest)
    {
        $oUser = $oRequest->user('api');

        return response()->json([
            'success' => true,
            'data' => $oUser->downloadsTracks->pluck('id')
        ]);
    }

    public function getGenres()
    {
        return response()->json([
            'success' => true,
            'data' => $this->trackService->getGenres(),
        ]);
    }

    public function getMoods()
    {
        return response()->json([
            'success' => true,
            'data' => $this->trackService->getMoods(),
        ]);
    }

    public function getUsageTypes()
    {
        return response()->json([
            'success' => true,
            'data' => $this->trackService->getUsageTypes(),
        ]);
    }

    public function getTags()
    {
        return response()->json([
            'success' => true,
            'data' => $this->trackService->getTags(),
        ]);
    }

    public function filterTracks(FilterTrackRequest $oRequest)
    {
        $aTracks = $this->trackService->getFilterTracks($oRequest);

        return response()->json([
            'success' => true,
            'data' => $aTracks
        ]);
    }

    public function searchTrack(SearchTrackRequest $oRequest)
    {
        $oTracks = $this->trackService->searchTrack($oRequest);
        return response()->json([
            'success' => true,
            'data' => $oTracks,
        ]);

    }

    public function getTrackTop()
    {
        $aTracks = $this->trackService->getTrackTop();

        return response()->json([
            'success' => true,
            'data' => $aTracks
        ]);
    }
}
