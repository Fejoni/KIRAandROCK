<?php

namespace App\Services;

use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ResetPasswordService
{
    public function resetByEmailRequest($aData)
    {
        $oUser = User::query()->where('email', $aData['email'])->first();
        if ($oUser) {
            PasswordReset::query()->where('email', $oUser->email)->delete();
            $sToken = Str::random(80);
            PasswordReset::query()->insert([
                'email' => $oUser->email,
                'token' => $sToken,
                'created_at' => Carbon::now()->toDateTimeString(),
            ]);

            $oUser->token = $sToken;

            return $oUser;
        }
    }
}