<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Services\SecurityService;

class SecurityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SecurityService::class, function ($app) {
            return new SecurityService();
        });
    }

    public function boot()
    {
        // تعيين سياسات الأمان
        $this->configureSecurityPolicies();
        
        // تعيين رسائل الخطأ
        $this->configureSecurityMessages();
    }

    protected function configureSecurityPolicies()
    {
        Gate::define('access-admin', function ($user) {
            return in_array($user->role, ['admin', 'super-admin']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->role === 'super-admin';
        });
    }

    protected function configureSecurityMessages()
    {
        $messages = [
            'unauthorized' => 'غير مصرح لك بالوصول إلى هذا المورد',
            'forbidden' => 'ليس لديك الصلاحيات الكافية للقيام بهذا الإجراء',
            'invalid_token' => 'رمز غير صالح',
            'session_expired' => 'انتهت صلاحية الجلسة'
        ];

        config(['security.messages' => $messages]);
    }
} 