<?php

namespace App\Services\Auth;

use App\Exceptions\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Symfony\Component\String\u;

class AuthService
{
    public function loginByEmail($aData): User
    {
        $oUser = User::query()->where('email', $aData['email'])->where('active', true)->first();

        return $this->checkPasswordAndLogin($oUser, $aData['password']);
    }

    /**
     * @throws ValidationException
     */
    private function checkPasswordAndLogin(User $oUser, $sPassword): User
    {
        if (!Hash::check($sPassword, $oUser->password)){
            throw new ValidationException(['password' => 'Wrong login or password.']);
        }

        return $oUser;
    }
}