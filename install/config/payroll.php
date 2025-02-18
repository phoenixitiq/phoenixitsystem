<?php

return [
    'payment_types' => ['full', 'half', 'advance'],
    'payment_methods' => ['bank_transfer', 'cash', 'cheque'],
    'payment_date' => 25,
    'advance_payment_day' => 15,
    'deductions' => [
        'max_monthly_deduction' => 40,
        'priority' => [
            'monthly_advance' => 1,
            'loan_installment' => 2,
            'other_deductions' => 3
        ]
    ]
]; 