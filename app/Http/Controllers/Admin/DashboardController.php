<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'employees' => Employee::count(),
            'active_users' => User::where('is_active', true)->count()
        ];

        $settings = Setting::all()->pluck('value', 'key');
        
        return view('admin.dashboard', compact('stats', 'settings'));
    }
} 