<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')->get();
        return view('admin.employees.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'department' => 'required|string|max:50',
            'position' => 'required|string|max:100',
            'join_date' => 'required|date',
            'salary' => 'required|numeric|min:0'
        ]);

        Employee::create($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'department' => 'required|string|max:50',
            'position' => 'required|string|max:100',
            'join_date' => 'required|date',
            'salary' => 'required|numeric|min:0'
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }
} 