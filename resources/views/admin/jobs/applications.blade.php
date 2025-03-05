@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">طلبات التوظيف</h2>
            <p class="text-gray-600">{{ $position->title }}</p>
        </div>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
            رجوع
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b">الاسم</th>
                    <th class="px-6 py-3 border-b">البريد الإلكتروني</th>
                    <th class="px-6 py-3 border-b">رقم الهاتف</th>
                    <th class="px-6 py-3 border-b">السيرة الذاتية</th>
                    <th class="px-6 py-3 border-b">الحالة</th>
                    <th class="px-6 py-3 border-b">تاريخ التقديم</th>
                    <th class="px-6 py-3 border-b">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td class="px-6 py-4">{{ $application->name }}</td>
                    <td class="px-6 py-4">{{ $application->email }}</td>
                    <td class="px-6 py-4">{{ $application->phone }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ Storage::url($application->cv_path) }}" 
                           target="_blank"
                           class="text-blue-600 hover:underline">
                            عرض السيرة الذاتية
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.applications.status', $application) }}" 
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" 
                                    onchange="this.form.submit()" 
                                    class="form-control">
                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>
                                    قيد المراجعة
                                </option>
                                <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>
                                    تمت المراجعة
                                </option>
                                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>
                                    مقبول
                                </option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>
                                    مرفوض
                                </option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        {{ $application->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4">
                        <button class="btn btn-sm btn-primary"
                                onclick="sendEmail('{{ $application->email }}')">
                            إرسال بريد
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection 