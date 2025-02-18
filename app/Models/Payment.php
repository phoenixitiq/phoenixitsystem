<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'subscription_id',
        'amount',
        'currency_code',
        'original_amount',
        'converted_amount',
        'payment_date',
        'payment_method',
        'status',
        'transaction_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->currency_code) {
                $payment->currency_code = app(CurrencyService::class)->getDefaultCurrency();
            }
            
            // حفظ المبلغ الأصلي
            $payment->original_amount = $payment->amount;
            
            // تحويل المبلغ إلى الدينار العراقي
            if ($payment->currency_code !== 'IQD') {
                $payment->converted_amount = app(CurrencyService::class)
                    ->convert($payment->amount, $payment->currency_code, 'IQD');
            } else {
                $payment->converted_amount = $payment->amount;
            }
        });
    }
} 