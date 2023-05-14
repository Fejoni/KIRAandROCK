<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\EmailVerifyRequest;
use App\Http\Requests\Api\Auth\RegisterByEmailRequest;
use App\Http\Requests\Api\Auth\ResendVerifyEmailRequest;
use App\Models\User;
use App\Services\Auth\RegisterService;
use App\Services\MailService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{

    private RegisterService $registerService;
    private UserService $userService;
    private MailService $mailService;

    public function __construct(RegisterService $oRegisterService, UserService $oUserService, MailService $oMailService)
    {
        $this->registerService = $oRegisterService;
        $this->userService = $oUserService;
        $this->mailService = $oMailService;
    }

    public function registerByEmail(RegisterByEmailRequest $oRequest): JsonResponse
    {
        $oUser = $this->registerService->registerByEmail($oRequest->validated());
        $this->mailService->sendMailVerifyRegisterByEmail($oUser);

        return response()->json([
            'success' => true,
            'data' => $this->userService->getUser($oUser)
        ]);
    }

    public function emailVerify(EmailVerifyRequest $oRequest): JsonResponse
    {
        $oUser = User::query()->where('verify_code', $oRequest->input('code'))->first();

        if ($oUser === null){
            return response()->json('Code not found', 404);
        }

        $result = $this->registerService->emailVerify($oUser, $oRequest->input('code'));

        return response()->json([
            'success' => $result,
            'data' => $this->userService->getUser($oUser),
        ]);
    }

    public function resendVerifyEmail(ResendVerifyEmailRequest $oRequest): JsonResponse
    {
        $oUser = User::query()->where('email', $oRequest->input('email'))->first();
        $oUser = $this->registerService->resendVerifyEmail($oUser);

        $this->mailService->sendMailVerifyRegisterByEmail($oUser);

        return response()->json([
            'success' => true,
            'data' => $this->userService->getUser($oUser),
        ]);
    }
}
