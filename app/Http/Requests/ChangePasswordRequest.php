<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password'=>['required','string','min:6'],
            'new_password'=>['required','string','min:6'],
        ];
    }
}
