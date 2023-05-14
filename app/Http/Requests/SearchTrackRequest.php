<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchTrackRequest extends FormRequest
{
    public function rules()
    {
        return [
            'search_value' => ['required']
        ];
    }
}
