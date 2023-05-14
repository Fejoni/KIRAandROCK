<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetEmailRequestRequest;
use App\Services\ResetPasswordService;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{

    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $oResetPasswordService)
    {
        $this->resetPasswordService = $oResetPasswordService;
    }

    public function resetByEmailRequest(PasswordResetEmailRequestRequest $oRequest)
    {
        $this->resetPasswordService->resetByEmailRequest($oRequest->validated());

    }
}
