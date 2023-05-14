<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['string'],
            'avatar' => ['nullable',Rule::dimensions()->minWidth(80)->minHeight(80)],
        ];
    }
}
