<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceFeature extends Model
{
    protected $fillable = [
        'service_id',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'icon',
        'is_featured',
        'display_order'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getTitle()
    {
        return app()->getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getDescription()
    {
        return app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en;
    }
} 