<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CheckForMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        if (App::isDownForMaintenance()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'النظام في وضع الصيانة'], 503);
            }
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
} 