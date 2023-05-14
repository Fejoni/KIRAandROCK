<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\SocialAccount;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $oUserService)
    {
        $this->userService = $oUserService;
    }

    public function redirecToProvider($sProvider)
    {
        $oSocialite = Socialite::with($sProvider);

        return $oSocialite->stateless()->redirect()->getTargetUrl();
    }

    public function handleProviderCallback($sProvider)
    {
        $oSocialUser = Socialite::driver($sProvider)->stateless()->user();
        $oSocialAccount = SocialAccount::query()->where('provider', $sProvider)->where('provider_id', $oSocialUser->getId())->first();
        $sUserAction = 'login';

        if ($oSocialAccount) {
            $oUser = $oSocialAccount->user;
            if (!empty($oUser)) {
                return response()->json([
                    'success' => true,
                    'data' => $this->userService->getUser($oUser),
                    'action' => $sUserAction,
                ]);
            } else {
                return response()->json('No user found', 404);
            }
        } else {
            $sEmail = $oSocialUser->getEmail();
            if (empty($sEmail)) {
                if (isset($oSocialUser->accessTokenResponseBody)) {
                    $aAccessTokenResponseBody = $oSocialUser->accessTokenResponseBody;
                    if (!empty($aAccessTokenResponseBody['email']))
                        $sEmail = $aAccessTokenResponseBody['email'];
                }
            }
            if (!empty($sEmail)) {
                $oUser = User::query()->where('email', $sEmail)->first();
                if (empty($oUser)) {
                    $oUser = new User();
                    $sNameAndSurname = explode(' ', $oSocialUser->getName());
                    $oUser->name = $sNameAndSurname[0];
                    $oUser->surname = $sNameAndSurname[1] ?? '';
                    $oUser->email = $sEmail;
                    $oUser->password = Hash::make(str_random(28));
                    $oUser->email_verified_at = date('Y-m-d H:i:s');
                    $oUser->api_token = Str::random(80);
                    $oUser->verify_code = null;
                    $oUser->verify_code_created_at = null;
                    $oUser->is_verified = '1';
                    $oUser->email_verified_at = date('Y-m-d H:i:s');
                    $oUser->save();
                    $sUserAction = 'registration';
                }

                $oUser->social_accounts()->create([
                    'provider' => $sProvider,
                    'provider_id' => $oSocialUser->getId(),
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $this->userService->getUser($oUser),
                    'action' => $sUserAction,
                ]);
            } else {
                return response()->json('User\'s email found', 404);
            }
        }
    }

    public function getUser(Request $oRequest)
    {
        $oUser = $oRequest->user('api');
        return response()->json([
           'success' => true,
           'data' => $this->userService->getUser($oUser),
        ]);
    }

    public function changePassword(ChangePasswordRequest $oRequest)
    {
        $oUser = $oRequest->user('api');
        $bResult = $this->userService->changePassword($oUser, $oRequest->input('password'), $oRequest->input('new_password'));

        return response()->json([
           'success' => $bResult,
        ]);
    }

    public function updateUserData(UserUpdateRequest $oRequest)
    {
        $oUser = $oRequest->user('api');
        $oUser = $this->userService->updateUserData($oUser, $oRequest->validated());

        $sUserImagePath = null;
        if ($oRequest->hasFile('avatar')){
            $sUserImagePath = $this->userService->attachAvatar($oUser, $oRequest->file('avatar'));
        }

        return response()->json([
            'success' => true,
            'data' => [
                'avatar' => !empty($sUserImagePath) ? env('BACKEND_URL').$sUserImagePath : '',
            ]
        ]);
    }

    public function changeEmail(ChangeEmailRequest $oRequest)
    {
        $oUser = $oRequest->user('api');
        $bResult = $this->userService->changeEmail($oUser, $oRequest->input('new_email'), $oRequest->input('password'));

        return response()->json([
            'success' => $bResult,
        ]);
    }
}
