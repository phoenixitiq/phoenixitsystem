<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'price',
        'is_active',
        'icon',
        'features',
        'category',
        'display_order'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    public function features()
    {
        return $this->hasMany(ServiceFeature::class)->orderBy('display_order');
    }

    public function getName()
    {
        return app()->getLocale() == 'ar' ? $this->name : $this->name_en;
    }

    public function getDescription()
    {
        return app()->getLocale() == 'ar' ? $this->description : $this->description_en;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)
                    ->whereHas('features', function($q) {
                        $q->where('is_featured', true);
                    })
                    ->orderBy('display_order');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
} 