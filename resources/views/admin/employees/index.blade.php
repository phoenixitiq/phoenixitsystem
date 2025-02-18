@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">إدارة الموظفين</h1>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة موظف جديد
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <select class="form-select w-auto" id="department-filter">
                    <option value="">كل الأقسام</option>
                    @foreach(config('constants.departments') as $key => $dept)
                        <option value="{{ $key }}">{{ $dept['ar'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>المنصب</th>
                            <th>القسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الترتيب</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                <img src="{{ $employee->image_url }}" alt="" class="rounded-circle" width="40">
                            </td>
                            <td>{{ json_decode($employee->name)->ar }}</td>
                            <td>{{ json_decode($employee->role)->ar }}</td>
                            <td>{{ config('constants.departments')[$employee->department]['ar'] }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->sort_order }}</td>
                            <td>
                                <span class="badge bg-{{ $employee->is_active ? 'success' : 'danger' }}">
                                    {{ $employee->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-item" 
                                            data-id="{{ $employee->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 