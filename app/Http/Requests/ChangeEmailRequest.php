<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'new_email' => ['required','email',],
            'password' => ['required','string',],
        ];
    }
}
