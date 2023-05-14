<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginByEmailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email', Rule::exists('users', 'email')->where('active', true)],
            'password' => ['required', 'string'],
        ];
    }
}
