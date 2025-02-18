<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            session()->flash('error', 'يجب تسجيل الدخول أولاً');
            return route('login');
        }
        return null;
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            abort(401, 'غير مصرح');
        }
        throw new \Illuminate\Auth\AuthenticationException(
            'يجب تسجيل الدخول للوصول إلى هذه الصفحة.', 
            $guards
        );
    }
}
