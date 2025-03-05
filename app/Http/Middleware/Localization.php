<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        // تحقق من وجود اللغة في الجلسة
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        
        // تحقق من وجود اللغة في الرابط
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (array_key_exists($locale, config('languages.available'))) {
                App::setLocale($locale);
                Session::put('locale', $locale);
            }
        }

        return $next($request);
    }
} 