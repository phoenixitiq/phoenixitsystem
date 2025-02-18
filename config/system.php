return [
    'hr' => [
        'work_shifts' => true,
        'min_advance_months' => 3, // الحد الأدنى لمدة عمل الموظف للحصول على سلفة
        'max_advance_percentage' => 50, // النسبة المئوية القصوى للسلفة من الراتب
        'advance_repayment_months' => 6, // الحد الأقصى لأشهر سداد السلفة
        'overtime_rates' => [
            'regular' => 1.5,
            'weekend' => 2.0,
            'holiday' => 2.5
        ],
        'advances' => [
            'types' => [
                'monthly_advance' => [
                    'max_percentage' => 50, // الحد الأقصى للسلفة من الراتب الشهري
                    'min_service_days' => 30, // الحد الأدنى لأيام الخدمة
                ],
                'loan' => [
                    'max_amount' => 10000, // الحد الأقصى لمبلغ القرض
                    'min_service_months' => 6, // الحد الأدنى لأشهر الخدمة
                    'max_installments' => 24, // الحد الأقصى لعدد الأقساط
                    'interest_rate' => 0, // نسبة الفائدة (0 للقروض الإسلامية)
                ]
            ],
            'approval_levels' => [
                'monthly_advance' => ['supervisor', 'hr_manager'],
                'loan' => ['hr_manager', 'finance_manager', 'ceo']
            ]
        ]
    ],
    
    'payroll' => [
        'payment_types' => ['full', 'half', 'advance'],
        'payment_methods' => ['bank_transfer', 'cash', 'cheque'],
        'payment_date' => 25, // يوم الدفع الشهري
        'advance_payment_day' => 15, // يوم دفع السلف
        'deductions' => [
            'max_monthly_deduction' => 40, // النسبة المئوية القصوى للخصم الشهري من الراتب
            'priority' => [
                'monthly_advance' => 1,
                'loan_installment' => 2,
                'other_deductions' => 3
            ]
        ]
    ]
]; 
