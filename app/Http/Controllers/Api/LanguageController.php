<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $locale = $request->get('locale');

        if (!in_array($locale, ['en', 'ar'])) {
            return response()->json(['message' => __('messages.language_not_supported')], 400);
        }

        App::setLocale($locale);
        app()->setLocale($locale);
        session(['locale' => $locale]);
        dd($locale);
        return response()->json(['message' => __('messages.language_changed_successfully'), 'locale' => $locale]);
    }
}

