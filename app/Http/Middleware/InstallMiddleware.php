<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class InstallMiddleware
{
    private function checkRequiredFiles()
    {
        return file_exists(base_path('.env.example')) && 
               is_writable(storage_path()) &&
               is_writable(base_path());
    }

    public function handle(Request $request, Closure $next)
    {
        $installed = file_exists(storage_path('app/installed'));

        if (!$installed && !$request->is('install*')) {
            return redirect('/install');
        }

        if ($installed && $request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
} 