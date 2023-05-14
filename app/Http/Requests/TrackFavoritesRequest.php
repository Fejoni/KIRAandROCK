<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackFavoritesRequest extends FormRequest
{
    public function rules()
    {
        return [
            'track' => ['required', 'integer'],
        ];
    }
}
