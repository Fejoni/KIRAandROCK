<?php

namespace App\Services;

use App\Models\Licensetemplate;
use Carbon\Carbon;

class LicenseService
{
    public function getDataLicenseForTrack($aUser, $aAuthorUser, $aTrack)
    {
        $sDataLicenseTemplate = Licensetemplate::first(['text']);
        $aData = $this->getDataAndPlaceholder($aUser, $aAuthorUser, $aTrack);

        foreach ($aData['data'] as $aValue){
            foreach ($aValue as $sKey => $sValue){
                if (in_array($sKey, $aData['placeholders'])){
                    $sDataLicenseTemplate->text = str_replace('['.$sKey.']', $sValue, $sDataLicenseTemplate->text);
                }
            }
        }
        return $sDataLicenseTemplate->text;
    }

    public function getDataAndPlaceholder($aUser, $aAuthorUser, $aTrack)
    {

        $aKeysTrack = ['id', 'name', 'description', 'temp', 'last_download_at', 'favorites_count', 'listened_count', 'downloaded_count'];
        $aKeysAuthor = ['name'];
        $aKeysUser = ['name', 'surname'];

        $aData['track'] = collect($aTrack)
            ->only($aKeysTrack)
            ->mapWithKeys(function ($value, $key){
                return ['track_' . $key => $value];
            })
            ->toArray();
        $aData['track']['track_link'] = $this->getLinkForTrack($aTrack['id']);

        $aData['author'] = collect($aAuthorUser)
            ->only($aKeysAuthor)
            ->mapWithKeys(function ($value, $key){
                return ['author_' . $key => $value];
            })
            ->toArray();

        $aData['user'] = collect($aUser)
            ->only($aKeysUser)
            ->mapWithKeys(function ($value, $key){
                return ['user_' . $key => $value];
            })
            ->toArray();

        $aData['other']['date_now'] = Carbon::now()->toDateTimeString().' UTC';

        $aKeysAll = collect()->merge($aData['track'])->merge($aData['author'])->merge($aData['user'])->keys()->toArray();

        return [
            'placeholders' => $aKeysAll,
            'data' => $aData
        ];

    }

    public function getLinkForTrack($iIdTrack)
    {
        return env('FRONTEND_URL').'/track/'.$iIdTrack;
    }
}