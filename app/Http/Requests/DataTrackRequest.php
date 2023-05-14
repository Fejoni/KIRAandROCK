<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataTrackRequest extends FormRequest
{
    public function rules()
    {
        return [
            'track_ids.*' => ['nullable']
        ];
    }
}
