<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class VerifyEmployeeImages
{
    public function handle(Request $request, Closure $next)
    {
        // نتحقق مرة واحدة فقط كل ساعة
        if (!Cache::has('employee_images_verified')) {
            $defaultImage = 'images/default-avatar.png';
            
            \App\Models\Employee::whereNull('image')
                ->orWhereRaw('NOT EXISTS (SELECT 1 FROM storage_files WHERE path = employees.image)')
                ->update(['image' => $defaultImage]);

            Cache::put('employee_images_verified', true, now()->addHour());
        }

        return $next($request);
    }
}