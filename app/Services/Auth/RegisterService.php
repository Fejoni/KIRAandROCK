<?php

namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterService
{
    public function registerByEmail($aData)
    {
        $sEmailVerifyCode = Str::random(50);
        $aData['verify_code'] = $sEmailVerifyCode;
        $aData['verify_code_created_at'] = Carbon::now()->toDateTimeString();

        $oUser = $this->register($aData);

        return $oUser;
    }

    public function register($aData)
    {
        DB::beginTransaction();
        $oUser = new User();
        $oUser->name = $aData['name'];
        $oUser->surname = $aData['surname'] ?? null;
        $oUser->email = $aData['email'];
        $oUser->api_token = Str::random(80);
        $oUser->is_verified = '0';
        $oUser->email_verified_at = $aData['email_verified_at'] ?? null;
        $oUser->verify_code = $aData['verify_code'] ?? null;
        $oUser->verify_code_created_at = $aData['verify_code_created_at'] ?? null;

        if (isset($aData['password'])) {
            $oUser->password = Hash::make($aData['password']);
        }

        $oUser->save();
        DB::commit();
        event(new Registered($oUser));

        return $oUser;
    }

    public function emailVerify(User $oUser, $sCode)
    {
        return $oUser->verify($sCode);
    }

    public function resendVerifyEmail(User $oUser)
    {
        $sEmailVerifyCode = Str::random(50);
        $oUser->verify_code = $sEmailVerifyCode;
        $oUser->verify_code_created_at = Carbon::now()->toDateTimeString();
        $oUser->save();

        return $oUser;
    }
}