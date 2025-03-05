<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'role_ar',
        'role_en',
        'bio_ar',
        'bio_en',
        'email',
        'image',
        'social_links',
        'department_id',
        'is_active',
        'display_order'
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
} 