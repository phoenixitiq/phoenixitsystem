<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'description',
        'package_type',
        'price',
        'currency',
        'billing_cycle',
        'min_duration',
        'max_duration',
        'features',
        'is_active',
        'posts_per_month',
        'platforms',
        'includes_strategy',
        'includes_monitoring',
        'response_time',
        'reports_frequency'
    ];

    protected $casts = [
        'features' => 'array',
        'platforms' => 'array',
        'is_active' => 'boolean',
        'includes_strategy' => 'boolean',
        'includes_monitoring' => 'boolean'
    ];

    // الباقات المتوفرة لإدارة السوشيال ميديا
    public static function getSocialMediaPackages()
    {
        return [
            'basic' => [
                'name' => 'الباقة الأساسية',
                'posts_per_month' => 15,
                'platforms' => ['facebook', 'instagram'],
                'includes_strategy' => false,
                'includes_monitoring' => false,
                'response_time' => '24 hours',
                'reports_frequency' => 'monthly'
            ],
            'professional' => [
                'name' => 'الباقة الاحترافية',
                'posts_per_month' => 30,
                'platforms' => ['facebook', 'instagram', 'twitter', 'linkedin'],
                'includes_strategy' => true,
                'includes_monitoring' => true,
                'response_time' => '12 hours',
                'reports_frequency' => 'bi-weekly'
            ],
            'enterprise' => [
                'name' => 'باقة الشركات',
                'posts_per_month' => 60,
                'platforms' => ['facebook', 'instagram', 'twitter', 'linkedin', 'tiktok'],
                'includes_strategy' => true,
                'includes_monitoring' => true,
                'response_time' => '6 hours',
                'reports_frequency' => 'weekly'
            ]
        ];
    }

    // حساب السعر مع الخصومات حسب مدة العقد
    public function calculatePrice(int $duration, string $billingCycle): array
    {
        $monthlyPrice = $this->price;
        $totalPrice = $monthlyPrice * $duration;

        // خصومات حسب مدة العقد
        $discount = match($duration) {
            3 => 0.05,  // 5% خصم للثلاث شهور
            6 => 0.10,  // 10% خصم للستة شهور
            12 => 0.15, // 15% خصم للسنة
            default => 0
        };

        // خصم إضافي للدفع المقدم
        if ($billingCycle === 'full_contract') {
            $discount += 0.05; // 5% خصم إضافي للدفع المقدم
        }

        $totalPrice = $totalPrice * (1 - $discount);

        return [
            'monthly_amount' => $monthlyPrice,
            'total_amount' => $totalPrice,
            'discount_percentage' => $discount * 100
        ];
    }

    public function getAvailableDurations(): array
    {
        return [
            1 => 'شهر واحد',
            3 => '3 شهور',
            6 => '6 شهور',
            12 => 'سنة كاملة'
        ];
    }

    public function getBillingCycles(): array
    {
        return [
            'monthly' => 'دفع شهري',
            'full_contract' => 'دفع كامل العقد مقدماً'
        ];
    }
} 