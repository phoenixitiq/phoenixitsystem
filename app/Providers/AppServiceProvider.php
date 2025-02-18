<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // مشاركة إعدادات النظام مع جميع الصفحات
        View::composer('*', function ($view) {
            $view->with('settings', Setting::all()->pluck('value', 'key'));
        });

        // إجبار HTTPS في الإنتاج
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // تعيين الحد الأقصى لطول السلسلة في قاعدة البيانات
        Schema::defaultStringLength(191);

        // إضافة قواعد تحقق مخصصة
        Validator::extend('strong_password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        });
    }
}
