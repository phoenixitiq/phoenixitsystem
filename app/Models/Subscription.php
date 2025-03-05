<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'duration',
        'billing_cycle',
        'status',
        'total_amount',
        'monthly_amount'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getNextPaymentDateAttribute()
    {
        if ($this->billing_cycle === 'full_contract') {
            return null;
        }

        $lastPayment = $this->payments()->latest('payment_date')->first();
        if (!$lastPayment) {
            return $this->start_date;
        }

        return $lastPayment->payment_date->addMonth();
    }

    public function getRemainingPaymentsAttribute()
    {
        if ($this->billing_cycle === 'full_contract') {
            return 0;
        }

        $paidMonths = $this->payments()->where('status', 'completed')->count();
        return $this->duration - $paidMonths;
    }
} 