<?php

/**
 * ترجمة نص
 */
function __($key, $params = []) {
    return App\Core\Language::translate($key, $params);
}

/**
 * تنسيق المسار
 */
function asset($path) {
    return rtrim(config('app.url'), '/') . '/assets/' . ltrim($path, '/');
}

/**
 * الحصول على قيمة من التكوين
 */
function config($key, $default = null) {
    return App\Core\Config::get($key, $default);
}

/**
 * تسجيل رسالة في السجل
 */
function log_message($level, $message) {
    return App\Core\Logger::write($level, $message);
}

if (!function_exists('getSocialLink')) {
    function getSocialLink($platform, $username) {
        $links = [
            'linkedin' => "https://linkedin.com/in/{$username}",
            'twitter' => "https://twitter.com/{$username}",
            'instagram' => "https://instagram.com/{$username}",
            'github' => "https://github.com/{$username}",
            'behance' => "https://behance.net/{$username}",
            'facebook' => "https://facebook.com/{$username}",
            'youtube' => "https://youtube.com/@{$username}",
            'tiktok' => "https://tiktok.com/@{$username}"
        ];
        
        return $links[$platform] ?? '#';
    }
}

if (!function_exists('getEmployeeImage')) {
    function getEmployeeImage($image) {
        if (empty($image)) {
            return asset('images/default-avatar.png');
        }

        $path = 'storage/' . $image;
        return file_exists(public_path($path)) 
            ? asset($path)
            : asset('images/default-avatar.png');
    }
}

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phone) {
        // تنسيق رقم الهاتف
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $phone);
    }
} 