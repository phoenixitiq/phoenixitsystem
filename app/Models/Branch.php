<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'address_ar',
        'address_en',
        'phone',
        'email',
        'working_hours_ar',
        'working_hours_en',
        'location_lat',
        'location_lng',
        'is_main',
        'country_code'
    ];

    public function getName()
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getAddress()
    {
        return app()->getLocale() == 'ar' ? $this->address_ar : $this->address_en;
    }

    public function getWorkingHours()
    {
        return app()->getLocale() == 'ar' ? $this->working_hours_ar : $this->working_hours_en;
    }

    public function getSocialLinks()
    {
        $country = strtolower($this->country_code);
        return [
            'facebook' => CompanySetting::get("social_{$country}_facebook"),
            'twitter' => CompanySetting::get("social_{$country}_twitter"),
            'instagram' => CompanySetting::get("social_{$country}_instagram"),
            'linkedin' => CompanySetting::get("social_{$country}_linkedin"),
            'tiktok' => CompanySetting::get("social_{$country}_tiktok")
        ];
    }
} 