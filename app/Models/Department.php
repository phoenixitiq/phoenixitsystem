<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'display_name_ar',
        'display_name_en'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
} 