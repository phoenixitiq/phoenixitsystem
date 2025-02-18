@extends('install.layout')

@section('content')
<div class="admin-setup">
    <h2>إعداد حساب المدير</h2>
    
    <form method="post" action="{{ route('install.admin.setup') }}">
        @csrf
        
        <div class="form-group">
            <label for="company_name">اسم الشركة</label>
            <input type="text" name="company_name" id="company_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="name">اسم المدير</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">إنشاء الحساب</button>
        </div>
    </form>
</div>
@endsection 