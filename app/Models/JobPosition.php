<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'department',
        'status'
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'position_id');
    }

    public function isOpen()
    {
        return $this->status === 'open';
    }
} 