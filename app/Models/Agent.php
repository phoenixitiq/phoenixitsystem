<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'business_type',
        'tax_number',
        'status',
        'commission_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function territories()
    {
        return $this->hasMany(AgentTerritory::class);
    }
} 