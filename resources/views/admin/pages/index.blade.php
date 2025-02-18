@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">إدارة صفحات الموقع</h1>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة صفحة جديدة
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الصفحة</th>
                            <th>عدد الأقسام</th>
                            <th>آخر تحديث</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                        <tr>
                            <td>{{ __("pages.{$page->identifier}") }}</td>
                            <td>{{ $page->sections_count }}</td>
                            <td>{{ $page->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.pages.sections.index', $page->identifier) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-list"></i> الأقسام
                                    </a>
                                    <a href="{{ route('admin.pages.edit', $page->identifier) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
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