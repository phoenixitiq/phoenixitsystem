<?php

return [
    'advances' => [
        'types' => [
            'monthly' => [
                'enabled' => true,
                'max_percentage' => 50,
                'deduction_type' => 'current_month'
            ],
            'loan' => [
                'enabled' => true,
                'max_amount' => 10000,
                'max_installments' => 24,
                'interest_enabled' => false
            ]
        ],
        'approvals' => [
            'monthly' => ['supervisor', 'hr_manager'],
            'loan' => ['hr_manager', 'finance_manager', 'ceo']
        ]
    ],
    
    'calculations' => [
        'deductions' => [
            'max_monthly_percentage' => 40,
            'priorities' => [
                1 => 'monthly_advance',
                2 => 'loan_installment',
                3 => 'other_deductions'
            ]
        ]
    ],

    'validations' => [
        'monthly_advance' => [
            'min_service_period' => 30, // أيام
            'max_requests_per_month' => 1,
            'blackout_days' => [1, 2, 3], // أيام غير مسموح فيها بطلب السلف
        ],
        'loan' => [
            'min_service_period' => 180, // أيام
            'max_active_loans' => 1,
            'min_salary' => 1000,
            'credit_check_required' => true
        ]
    ],

    'notifications' => [
        'channels' => [
            'whatsapp' => [
                'enabled' => true,
                'provider' => 'twilio', // أو أي مزود آخر
                'template_namespace' => 'your_namespace',
                'default_language' => 'ar'
            ],
            'email' => [
                'enabled' => true,
                'queue' => 'notifications'
            ],
            'sms' => [
                'enabled' => true,
                'provider' => 'twilio'
            ]
        ],
        'templates' => [
            'payment_due' => [
                'whatsapp' => 'payment_due_whatsapp',
                'email' => 'payment_due_email',
                'sms' => 'payment_due_sms'
            ],
            'payment_overdue' => [
                'whatsapp' => 'payment_overdue_whatsapp',
                'email' => 'payment_overdue_email',
                'sms' => 'payment_overdue_sms'
            ],
            'invoice' => [
                'whatsapp' => 'invoice_whatsapp',
                'email' => 'invoice_email'
            ],
            'payment_link' => [
                'whatsapp' => 'payment_link_whatsapp',
                'email' => 'payment_link_email'
            ]
        ],
        'schedules' => [
            'payment_reminder' => [
                'before_due' => [5, 3, 1], // أيام قبل موعد السداد
                'after_due' => [1, 3, 7, 15] // أيام بعد تأخر السداد
            ],
            'retry_failed' => 30, // دقائق قبل إعادة المحاولة
            'max_retries' => 3
        ]
    ]
]; 