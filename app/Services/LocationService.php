<?php

namespace App\Services;

use App\Models\IpLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.ip_api.key');
    }

    public function getLocationInfo($ip)
    {
        // التحقق من الكاش أولاً
        $cacheKey = 'ip_info_' . $ip;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // استعلام API للحصول على معلومات IP
        $response = Http::get("http://ip-api.com/json/{$ip}");
        $data = $response->json();

        if ($response->successful()) {
            // تخزين في الكاش لمدة يوم
            Cache::put($cacheKey, $data, now()->addDay());

            // تسجيل المعلومات
            IpLog::create([
                'user_id' => auth()->id(),
                'ip_address' => $ip,
                'country_code' => $data['countryCode'],
                'country_name' => $data['country'],
                'city' => $data['city'],
                'currency_code' => $this->getCurrencyByCountry($data['countryCode']),
                'language_code' => $this->getLanguageByCountry($data['countryCode'])
            ]);

            return $data;
        }

        return null;
    }

    public function getCurrencyByCountry($countryCode)
    {
        $currencies = [
            // الدولار الأمريكي للدول التي تستخدم الدولار
            'US' => 'USD', // الولايات المتحدة
            'AE' => 'USD', // الإمارات
            'SA' => 'USD', // السعودية
            'BH' => 'USD', // البحرين
            
            // اليورو للدول الأوروبية
            'DE' => 'EUR', // ألمانيا
            'FR' => 'EUR', // فرنسا
            'IT' => 'EUR', // إيطاليا
            'ES' => 'EUR', // إسبانيا
            
            // الدينار للدول العربية
            'IQ' => 'IQD', // الدينار العراقي
            'KW' => 'KWD', // الدينار الكويتي
            'JO' => 'JOD', // الدينار الأردني
            'LY' => 'LYD'  // الدينار الليبي
        ];

        // العملة الافتراضية هي الدولار
        return $currencies[$countryCode] ?? 'USD';
    }

    public function getLanguageByCountry($countryCode)
    {
        $languages = [
            'SA' => 'ar',
            'AE' => 'ar',
            'US' => 'en',
            'GB' => 'en',
            // إضافة المزيد من اللغات
        ];

        return $languages[$countryCode] ?? 'en';
    }
} 