@extends('install.layout')

@section('content')
<div class="finish-section text-center">
    <div class="success-icon mb-4">
        <i class="fas fa-check-circle fa-5x text-success"></i>
    </div>
    
    <h2 class="mb-4">تم تثبيت النظام بنجاح!</h2>
    <p class="text-muted mb-5">يمكنك الآن تسجيل الدخول إلى لوحة التحكم</p>

    <div class="login-info mb-5">
        <div class="alert alert-info">
            <h4 class="alert-heading mb-3">معلومات تسجيل الدخول</h4>
            <p class="mb-2">البريد الإلكتروني: {{ $admin_email }}</p>
            <p class="mb-0">كلمة المرور: التي قمت بإدخالها أثناء التثبيت</p>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg">
            الذهاب إلى لوحة التحكم
            <i class="fas fa-arrow-left me-2"></i>
        </a>
    </div>

    <div class="additional-info mt-5">
        <div class="alert alert-warning">
            <h4 class="alert-heading mb-3">ملاحظات هامة</h4>
            <ul class="list-unstyled text-start mb-0">
                <li class="mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    يرجى حذف مجلد التثبيت "install" من الخادم
                </li>
                <li class="mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    تأكد من تعيين الصلاحيات المناسبة للملفات والمجلدات
                </li>
                <li>
                    <i class="fas fa-sync me-2"></i>
                    قم بعمل نسخة احتياطية بشكل دوري
                </li>
            </ul>
        </div>
    </div>
</div>

@push('styles')
<style>
.finish-section {
    max-width: 600px;
    margin: 0 auto;
}

.success-icon {
    font-size: 2rem;
}

.login-info {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.additional-info ul li {
    padding: 10px 0;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.additional-info ul li:last-child {
    border-bottom: none;
}
</style>
@endpush 