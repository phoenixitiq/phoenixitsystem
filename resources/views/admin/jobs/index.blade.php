@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">إدارة الوظائف</h2>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
            إضافة وظيفة جديدة
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b">العنوان</th>
                    <th class="px-6 py-3 border-b">القسم</th>
                    <th class="px-6 py-3 border-b">الحالة</th>
                    <th class="px-6 py-3 border-b">عدد الطلبات</th>
                    <th class="px-6 py-3 border-b">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($positions as $position)
                <tr>
                    <td class="px-6 py-4">{{ $position->title }}</td>
                    <td class="px-6 py-4">{{ $position->department }}</td>
                    <td class="px-6 py-4">
                        <span class="badge {{ $position->status === 'open' ? 'badge-success' : 'badge-danger' }}">
                            {{ $position->status === 'open' ? 'مفتوحة' : 'مغلقة' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $position->applications_count }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.jobs.applications', $position) }}" 
                           class="btn btn-sm btn-secondary">
                            عرض الطلبات
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 