<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CurrencyService
{
    public function convert($amount, $from = 'IQD', $to = 'IQD')
    {
        if ($from === $to) {
            return $amount;
        }

        $rates = $this->getExchangeRates();
        
        // التحويل إلى الدينار العراقي أولاً
        $inIQD = $amount / $rates[$from];
        
        // التحويل إلى العملة المطلوبة
        return $inIQD * $rates[$to];
    }

    public function formatPrice($amount, $currency = 'IQD')
    {
        $currencies = [
            'IQD' => ['symbol' => 'د.ع', 'position' => 'after'],
            'USD' => ['symbol' => '$', 'position' => 'before'],
            'EUR' => ['symbol' => '€', 'position' => 'before']
        ];

        $info = $currencies[$currency] ?? $currencies['IQD'];
        $formattedAmount = number_format($amount, 2);

        return $info['position'] === 'before' 
            ? $info['symbol'] . $formattedAmount
            : $formattedAmount . ' ' . $info['symbol'];
    }

    public function setDefaultCurrency($currencyCode)
    {
        try {
            DB::beginTransaction();
            
            // إلغاء العملة الافتراضية الحالية
            Currency::where('is_default', true)
                ->update(['is_default' => false]);
            
            // تعيين العملة الجديدة كافتراضية
            Currency::where('code', $currencyCode)
                ->update(['is_default' => true]);
            
            DB::commit();
            Cache::forget('default_currency');
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function getDefaultCurrency()
    {
        return Cache::remember('default_currency', now()->addDay(), function () {
            return Currency::where('is_default', true)->first()->code ?? 'IQD';
        });
    }

    protected function getExchangeRates()
    {
        return Cache::remember('exchange_rates', now()->addHour(), function () {
            return Currency::pluck('exchange_rate', 'code')->toArray();
        });
    }

    public function getSupportedCurrencies()
    {
        return [
            'IQD' => 'دينار عراقي',
            'USD' => 'دولار أمريكي',
            'EUR' => 'يورو'
        ];
    }
} 