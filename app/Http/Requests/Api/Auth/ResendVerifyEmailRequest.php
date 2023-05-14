<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResendVerifyEmailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'string', Rule::exists('users', 'email')]
        ];
    }
}
