<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EmailVerifyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => ['required', 'string']
        ];
    }
}
