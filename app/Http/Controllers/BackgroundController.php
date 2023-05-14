<?php

namespace App\Http\Controllers;

use App\Services\BackgroundService;
use Illuminate\Http\Request;

class BackgroundController extends Controller
{
    private BackgroundService $backgroundService;

    public function __construct(BackgroundService $oBackgroundService)
    {
        $this->backgroundService = $oBackgroundService;
    }

    public function getBackground()
    {
        $oBackground = $this->backgroundService->getBackground();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $oBackground->id,
                'image' => ($oBackground->image) ? env('BACKEND_URL').$oBackground->image->path : '',
                'video' => ($oBackground->video) ? env('BACKEND_URL').$oBackground->video->path : '',
            ]
        ]);
    }
}
