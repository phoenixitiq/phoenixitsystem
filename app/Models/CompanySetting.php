<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CompanySetting extends Model
{
    protected $fillable = [
        'key_name',
        'value',
        'group_name'
    ];

    // الحصول على قيمة إعداد معين
    public static function get($key, $default = null)
    {
        return Cache::remember('setting_'.$key, 3600, function() use ($key, $default) {
            $setting = self::where('key_name', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    // الحصول على مجموعة من الإعدادات
    public static function getGroup($group)
    {
        return Cache::remember('settings_group_'.$group, 3600, function() use ($group) {
            return self::where('group_name', $group)
                      ->pluck('value', 'key_name')
                      ->toArray();
        });
    }

    // تحديث أو إنشاء إعداد
    public static function set($key, $value, $group = 'general')
    {
        $setting = self::updateOrCreate(
            ['key_name' => $key],
            ['value' => $value, 'group_name' => $group]
        );

        Cache::forget('setting_'.$key);
        Cache::forget('settings_group_'.$group);

        return $setting;
    }

    public static function getLegalInfo()
    {
        return self::getGroup('legal');
    }

    public static function getPaymentInfo()
    {
        return self::getGroup('payment');
    }

    public static function getSupportInfo()
    {
        return self::getGroup('support');
    }

    public static function getLocation()
    {
        return [
            'lat' => self::get('company_location_lat'),
            'lng' => self::get('company_location_lng'),
            'address_ar' => self::get('company_address_ar'),
            'address_en' => self::get('company_address_en'),
            'map_url' => self::get('company_map_url')
        ];
    }

    public static function getPaymentMethods()
    {
        $methods = self::get('payment_methods');
        return $methods ? explode(',', $methods) : [];
    }

    public static function getBankInfo()
    {
        return [
            'bank_name' => self::get('bank_name'),
            'account_name' => self::get('bank_account_name'),
            'iban' => self::get('bank_iban')
        ];
    }

    public static function getContactNumbers()
    {
        return [
            'main' => self::get('company_phone_ksa'),
            'whatsapp' => self::get('company_whatsapp'),
            'support' => self::get('technical_support_number'),
            'sales' => self::get('sales_number')
        ];
    }

    public static function getBranchInfo($branch = 'uk')
    {
        return [
            'address' => self::get("branch_{$branch}_address"),
            'hours' => self::get("branch_{$branch}_hours"),
            'social' => [
                'facebook' => self::get("social_{$branch}_facebook"),
                'twitter' => self::get("social_{$branch}_twitter"),
                'linkedin' => self::get("social_{$branch}_linkedin"),
                'instagram' => self::get("social_{$branch}_instagram"),
                'tiktok' => self::get("social_{$branch}_tiktok")
            ]
        ];
    }

    public static function getStats()
    {
        return [
            'clients' => self::get('team_stats_clients'),
            'projects' => self::get('team_stats_projects'),
            'experience' => self::get('team_stats_experience'),
            'team' => self::get('team_stats_team')
        ];
    }

    public static function getServicesStats()
    {
        return [
            'social_media' => self::get('services_social_media_count'),
            'websites' => self::get('services_websites_count'),
            'apps' => self::get('services_apps_count'),
            'designs' => self::get('services_designs_count')
        ];
    }
} 