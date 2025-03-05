<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Career;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
        $this->middleware('permission:view-team')->only(['index', 'show']);
        $this->middleware('permission:manage-team')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        // جلب جميع الأقسام
        $departments = Department::orderBy('id')->get();
        
        // إعداد استعلام الموظفين
        $employeesQuery = Employee::with('department')
            ->where('is_active', true)
            ->orderBy('display_order');
        
        // تصفية حسب القسم
        if ($request->has('department')) {
            $employeesQuery->where('department_id', $request->department);
        }
        
        $employees = $employeesQuery->get();
        
        return view('pages.team', compact('departments', 'employees'));
    }
} 