<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class InstallMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // التحقق من وجود ملف .env
        if (!file_exists(base_path('.env'))) {
            return $next($request);
        }

        // التحقق من وجود جداول قاعدة البيانات
        try {
            if (!Schema::hasTable('users')) {
                return $next($request);
            }
        } catch (\Exception $e) {
            return $next($request);
        }

        // إذا تم التثبيت مسبقاً، قم بالتحويل إلى الصفحة الرئيسية
        return redirect('/');
    }
} 