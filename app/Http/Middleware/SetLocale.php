<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // 1- الأول شوف الـ session
        $locale = Session::get('locale');

        // 2- لو مفيش سيشن، شوف param
        if (! $locale && $request->has('locale')) {
            $locale = $request->get('locale');
        }

        // 3- لو مفيش سيشن ولا param، خد من الهيدر
        if (! $locale && $request->hasHeader('Accept-Language')) {
            $locale = $this->parseAcceptLanguage($request->header('Accept-Language'));
        }

        // 4- fallback للديفولت
        if (! $locale) {
            $locale = config('app.locale');
        }

        // 5- set فعلي للغة
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }

        return $next($request);
    }

    protected function parseAcceptLanguage(?string $header): ?string
    {
        if (! $header) {
            return null;
        }

        $supported = ['en', 'ar'];
        $candidates = [];

        foreach (explode(',', $header) as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            $segments = explode(';', $part);
            $tag = strtolower(trim($segments[0]));
            $quality = 1.0;

            foreach (array_slice($segments, 1) as $param) {
                if (str_starts_with(trim($param), 'q=')) {
                    $quality = (float) substr(trim($param), 2);
                }
            }

            $language = explode('-', $tag)[0];
            if (in_array($language, $supported, true)) {
                $candidates[$language] = max($candidates[$language] ?? 0, $quality);
            }
        }

        if ($candidates === []) {
            return null;
        }

        arsort($candidates);

        return array_key_first($candidates);
    }
}
