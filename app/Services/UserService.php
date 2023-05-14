<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function getUser(User $oUser)
    {
        $aResult = [];
        $aResult['id'] = $oUser->id;
        $aResult['name'] = $oUser->name;
        $aResult['surname'] = $oUser->surname;
        $aResult['email'] = $oUser->email;
        $aResult['avatar'] = $oUser->main_image->path ?? '';
        $aResult['api_token'] = $oUser->api_token;
        $aResult['is_verified'] = ($oUser->is_verified == 1) ? true : false;
        $aResult['created_at'] = $oUser->created_at;

        return $aResult;
    }

    public function changePassword(User $oUser, $sCurrentPass, $sNewPass)
    {
        if (Hash::check($sCurrentPass, $oUser->password)){
            $oUser->password = Hash::make($sNewPass);
            $oUser->save();
            return true;
        }
        return false;
    }

    public function updateUserData(User $oUser, $aData)
    {
        $oUser->name = $aData['name'] ?? $oUser->name;
        $oUser->save();
        return $oUser;
    }

    public function attachAvatar(User $oUser, UploadedFile $oFile)
    {
        $sFileName = Str::random() . '.' . $oFile->getClientOriginalName();
        $aFilePath = $oFile->storeAs('users', $sFileName, 'upload');
        $this->deleteAvatar($oUser);
        $oUser->main_image()->create(['path' => '/uploads/' . $aFilePath, 'is_main' => 1, 'order' => 1]);

        return '/uploads/'.$aFilePath;
    }

    public function deleteAvatar(User $oUser)
    {
        if (!empty($oUser->main_image)){
            $oUser->main_image->remove();
        }
    }

    public function changeEmail(User $oUser, $sNewEmail, $sPassword)
    {
        if (Hash::check($sPassword, $oUser->password)){
            $oUser->email = $sNewEmail;
            $oUser->save();
            return true;
        }

        return false;
    }
}