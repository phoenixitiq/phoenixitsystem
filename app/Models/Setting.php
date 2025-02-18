<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group_name'];

    public static function get($key, $default = null)
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set($key, $value, $group = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group_name' => $group
            ]
        );

        Cache::forget("setting.{$key}");
        return $setting;
    }

    public static function getGroup($group)
    {
        return self::where('group_name', $group)
                   ->pluck('value', 'key')
                   ->toArray();
    }
} 