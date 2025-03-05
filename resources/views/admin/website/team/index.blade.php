@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">إدارة فريق العمل</h1>
        <a href="{{ route('admin.team.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة عضو جديد
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <select class="form-select w-auto" id="department-filter">
                    <option value="">كل الأقسام</option>
                    <option value="management">الإدارة</option>
                    <option value="tech">التقنية</option>
                    <option value="design">التصميم</option>
                    <option value="marketing">التسويق</option>
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
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr>
                            <td>
                                <img src="{{ $member->image }}" alt="" class="rounded-circle" width="40">
                            </td>
                            <td>{{ json_decode($member->name)->ar }}</td>
                            <td>{{ json_decode($member->role)->ar }}</td>
                            <td>{{ $member->department }}</td>
                            <td>
                                <span class="badge bg-{{ $member->is_active ? 'success' : 'danger' }}">
                                    {{ $member->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.team.edit', $member) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-item" 
                                            data-id="{{ $member->id }}">
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