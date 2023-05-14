<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterTrackRequest extends FormRequest
{
    public function rules()
    {
        return [
            'search_value.*' => ['nullable', 'string'],
            'search_type' => ['nullable', 'string'],
            'max_bpm' => ['nullable', 'integer', 'between:0,300'],
            'min_bpm' => ['nullable', 'integer', 'between:0,300'],
            'track_ids.*' => ['nullable']
        ];
    }
}
