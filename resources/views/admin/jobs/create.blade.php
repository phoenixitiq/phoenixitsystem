@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">إضافة وظيفة جديدة</h2>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
            رجوع
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.jobs.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="title">عنوان الوظيفة</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="department">القسم</label>
                    <select name="department" id="department" class="form-control" required>
                        <option value="تطوير">تطوير</option>
                        <option value="تسويق">تسويق</option>
                        <option value="تصميم">تصميم</option>
                        <option value="إدارة">إدارة</option>
                    </select>
                </div>

                <div class="form-group col-span-2">
                    <label for="description">الوصف الوظيفي</label>
                    <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
                </div>

                <div class="form-group col-span-2">
                    <label for="requirements">المتطلبات</label>
                    <textarea name="requirements" id="requirements" rows="5" class="form-control" required></textarea>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection 