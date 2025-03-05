<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieConsent
{
    public function handle(Request $request, Closure $next)
    {
        if (!Cookie::has(config('cookies.consent.cookie_name'))) {
            // إذا كان الطلب AJAX، نتجاهل التحقق من موافقة الكوكيز
            if (!$request->ajax()) {
                view()->share('showCookieConsent', true);
            }
        }

        return $next($request);
    }
} 