@extends('install.layout')

@section('content')
<div class="step-content">
    <h3 class="mb-4">إعداد النظام</h3>
    
    <form action="{{ route('install.system.setup') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">اسم التطبيق</label>
            <input type="text" name="app_name" class="form-control" value="Phoenix IT" required>
        </div>

        <div class="mb-3">
            <label class="form-label">رابط الموقع</label>
            <input type="url" name="app_url" class="form-control" value="https://" required>
        </div>

        <div class="mb-4">
            <h4 class="mb-3">إعدادات البريد الإلكتروني</h4>
            
            <div class="mb-3">
                <label class="form-label">خادم SMTP</label>
                <input type="text" name="mail_host" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">المنفذ</label>
                <input type="number" name="mail_port" class="form-control" value="587" required>
            </div>

            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="mail_username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="mail_password" class="form-control" required>
            </div>
        </div>

        <div class="mb-4">
            <h4 class="mb-3">إعدادات النسخ الاحتياطي</h4>
            
            <div class="mb-3">
                <label class="form-label">مزود التخزين</label>
                <select name="backup_disk" class="form-select" required>
                    <option value="local">محلي</option>
                    <option value="s3">Amazon S3</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('install.database') }}" class="btn btn-secondary">السابق</a>
            <button type="submit" class="btn btn-primary">التالي</button>
        </div>
    </form>
</div>
@endsection 