<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeContract extends Model
{
    protected $fillable = [
        'employee_id',
        'contract_number',
        'start_date',
        'end_date',
        'salary',
        'position',
        'contract_type',
        'benefits',
        'terms'
    ];

    protected $casts = [
        'benefits' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_by_employee' => 'boolean',
        'signed_by_company' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               now()->between($this->start_date, $this->end_date);
    }
} 