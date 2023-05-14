<?php

namespace App\Services;

use App\Mail\ConfirmRegisterByEmail;
use App\Mail\VerifyRegisterByEmail;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendMailVerifyRegisterByEmail($aData)
    {
        $aData['verify_link'] = env('FRONTEND_URL').'?email_verification='.$aData['verify_code'];

        Mail::to(trim($aData['email']))->send(new VerifyRegisterByEmail([
            'name' => $aData['name'],
            'verify_link' => $aData['verify_link'],
        ]));
    }

    public function sendMailResetPasswordByEmail($aData, $sToken)
    {
        $aData['password_reset_link'] = env('FRONTEND_URL').'/reset/password/'.$sToken;
    }
}