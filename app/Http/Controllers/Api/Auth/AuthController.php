<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginByEmailRequest;
use App\Services\Auth\AuthService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;
    private UserService $userService;
    
    public function __construct(AuthService $oAuthService, UserService $oUserService)
    {
        $this->authService = $oAuthService;
        $this->userService = $oUserService;
    }

    public function loginByEmail(LoginByEmailRequest $oRequest): JsonResponse
    {

        $oUser = $this->authService->loginByEmail($oRequest->validated());
        return response()->json([
            'success' => true,
            'data' => $this->userService->getUser($oUser),
        ]);
    }
}
