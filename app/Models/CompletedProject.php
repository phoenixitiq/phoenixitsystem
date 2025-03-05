<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompletedProject extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'client_name',
        'completion_date',
        'category',
        'image_path',
        'link',
        'is_featured'
    ];

    protected $dates = [
        'completion_date',
        'created_at'
    ];

    public function getTitle()
    {
        return app()->getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getDescription()
    {
        return app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getImageUrl()
    {
        return asset('storage/' . $this->image_path);
    }
} 