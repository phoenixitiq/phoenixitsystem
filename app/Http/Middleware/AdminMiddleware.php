<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'super-admin'])) {
            return redirect('/')->with('error', 'غير مصرح لك بالوصول');
        }

        return $next($request);
    }
} 