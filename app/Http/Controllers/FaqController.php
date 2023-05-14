<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Privacypolicy;
use App\Models\Useterm;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function getFaq()
    {
        return response()->json([
            'success' => true,
            'data' => Faq::get(['id','question', 'answer',]),
        ]);
    }

    public function getPrivacyPolicy()
    {
        return response()->json([
            'success' => true,
            'data' => Privacypolicy::get(['id','title', 'text',]),
        ]);
    }
    
    public function getUseTerm()
    {
        return response()->json([
            'success' => true,
            'data' => Useterm::get(['id','title', 'text',]),
        ]);
    }
}
