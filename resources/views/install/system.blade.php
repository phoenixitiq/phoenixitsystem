@extends('install.layout')

@section('content')
<div class="system-section">
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        يرجى إدخال المعلومات الأساسية للنظام
    </div>

    <form action="{{ route('install.system.setup') }}" method="POST" class="system-form">
        @csrf
        
        <div class="form-section mb-4">
            <h3 class="section-title">معلومات الموقع</h3>
            
            <div class="form-group mb-3">
                <label for="site_name" class="form-label">اسم الموقع</label>
                <input type="text" class="form-control" id="site_name" name="site_name" 
                       value="{{ old('site_name', 'Phoenix IT') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="site_url" class="form-label">رابط الموقع</label>
                <input type="url" class="form-control" id="site_url" name="site_url" 
                       value="{{ old('site_url', request()->root()) }}" required>
            </div>
        </div>

        <div class="form-section mb-4">
            <h3 class="section-title">معلومات المدير</h3>
            
            <div class="form-group mb-3">
                <label for="admin_name" class="form-label">الاسم</label>
                <input type="text" class="form-control" id="admin_name" name="admin_name" 
                       value="{{ old('admin_name') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="admin_email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="admin_email" name="admin_email" 
                       value="{{ old('admin_email') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="admin_password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
            </div>

            <div class="form-group mb-3">
                <label for="admin_password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" class="form-control" id="admin_password_confirmation" 
                       name="admin_password_confirmation" required>
            </div>
        </div>

        <div class="action-buttons text-center mt-5">
            <button type="submit" class="btn btn-primary">
                إكمال التثبيت
                <i class="fas fa-arrow-left me-2"></i>
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
.system-section {
    max-width: 600px;
    margin: 0 auto;
}

.system-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.section-title {
    font-size: 1.25rem;
    color: var(--primary-color);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 8px;
}
</style>
@endpush 