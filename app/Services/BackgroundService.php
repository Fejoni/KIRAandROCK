<?php

namespace App\Services;

use App\Models\Background;
use App\Models\Video;

class BackgroundService
{
    public function uploadVideo($oBackground, $oVideo, $sFolder)
    {
        $aVideoPath = $this->getPathVideo($sFolder, $oBackground->id);
        $oVideoBackground = $oBackground->video;
        if (!empty($oVideoBackground)){
            unlink($oVideoBackground->path);
            $oVideo->move($aVideoPath['videoPath'], $aVideoPath['videoName']);
            $oVideoBackground->path = $aVideoPath['videoPath'];
            $oVideoBackground->save();
        }else {
            $oVideo->move($aVideoPath['videoPath'], $aVideoPath['videoName']);
            $oBackground->video()->create(['path' => $aVideoPath['videoPath'].'/'.$aVideoPath['videoName']]);
        }

    }

    public function getPathVideo($sFolder, $iBackgroundId)
    {
        if (!file_exists(public_path('uploads/'.$sFolder))){
            mkdir(public_path('uploads/'.$sFolder.'/'), 0755, true);
        }

        $sFileName = str_random(10).'_'.time().'.mp4';
        $sFolderName = $sFolder.'_'.$iBackgroundId;
        $sVideoPath = 'uploads/'.$sFolder.'/'.$sFolderName;

        return [
            'videoName' => $sFileName,
            'videoPath' => $sVideoPath,
        ];
    }

    public function getBackground()
    {
        return Background::query()->where('published', '1')->first();
    }
}