<?php

namespace App\Services;

class PayrollRequirements
{
    public function check()
    {
        return [
            'database' => $this->checkDatabase(),
            'settings' => $this->checkSettings(),
            'permissions' => $this->checkPermissions()
        ];
    }

    private function checkDatabase()
    {
        return [
            'salary_tables' => $this->checkSalaryTables(),
            'advance_tables' => $this->checkAdvanceTables(),
            'loan_tables' => $this->checkLoanTables()
        ];
    }

    private function checkSettings()
    {
        return [
            'payment_types' => config('system.payroll.payment_types'),
            'advance_settings' => config('system.hr.advances'),
            'deduction_rules' => config('system.payroll.deductions')
        ];
    }

    private function checkPermissions()
    {
        return [
            'roles' => [
                'payroll_manager' => $this->checkRole('payroll_manager'),
                'loan_officer' => $this->checkRole('loan_officer')
            ],
            'permissions' => [
                'basic' => $this->checkBasicPermissions(),
                'advanced' => $this->checkAdvancedPermissions()
            ]
        ];
    }

    private function checkLoanTables()
    {
        return [
            'structure' => $this->checkLoanTableStructure(),
            'relationships' => $this->checkLoanRelationships(),
            'constraints' => $this->checkLoanConstraints()
        ];
    }

    private function checkAdvanceSystem()
    {
        return [
            'monthly_advance' => [
                'enabled' => config('payroll.advances.types.monthly.enabled'),
                'max_percentage' => config('payroll.advances.types.monthly.max_percentage'),
                'deduction_type' => config('payroll.advances.types.monthly.deduction_type')
            ],
            'loan_system' => [
                'enabled' => config('payroll.advances.types.loan.enabled'),
                'max_amount' => config('payroll.advances.types.loan.max_amount'),
                'installments' => config('payroll.advances.types.loan.max_installments')
            ]
        ];
    }
} 