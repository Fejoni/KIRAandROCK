<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\File;
use App\Models\Track;
use App\Models\User;
use App\Services\TrackService;
use App\Services\UserService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private TrackService $trackService;
    private UserService $userService;

    public function __construct(TrackService $oTrackService, UserService $oUserService)
    {
        $this->trackService = $oTrackService;
        $this->userService = $oUserService;
    }

    public function show()
    {
        return view('test.test');
    }

    public function form(Request $oRequest)
    {
        if ($oRequest->hasFile('demo')){
            $sTrackPath = 'uploads/track/track_123/randomname_123.mp3';
        }


        $oModel = Track::find(1);

        $oModel->file()->create(['type' => 'demo','path' => $sTrackPath]);

        $file = $oRequest->file('demo')->move(public_path('uploads/'), 'ttt.mp3');

    }

    public function uploadImageFromUser(UserUpdateRequest $oRequest)
    {
        $oUser = User::query()->where('id', '=','10')->first();
        $sResult = null;
        if ($oRequest->hasFile('avatar')){
            $sResult = $this->userService->attachAvatar($oUser, $oRequest->file('avatar'));
        }

        dd($sResult);
    }
}
