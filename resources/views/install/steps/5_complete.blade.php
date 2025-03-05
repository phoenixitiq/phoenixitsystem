@extends('install.layouts.master')

@section('content')
<div class="text-center completion-page">
    <div class="success-animation">
        <i class="bi bi-check-circle text-success"></i>
    </div>
    
    <h2 class="mt-4 mb-4">تم تثبيت النظام بنجاح!</h2>
    
    <p class="lead mb-4">
        تم إعداد النظام بنجاح ويمكنك الآن البدء باستخدامه
    </p>

    <div class="alert alert-info mb-4">
        <h5 class="alert-heading mb-3">
            <i class="bi bi-shield-lock"></i>
            بيانات الدخول الافتراضية
        </h5>
        <div class="credentials">
            <div class="credential-item">
                <span class="label">البريد الإلكتروني:</span>
                <code>admin@phoenixitiq.com</code>
            </div>
            <div class="credential-item">
                <span class="label">كلمة المرور:</span>
                <code>admin123</code>
            </div>
        </div>
        <hr>
        <small class="text-danger">
            <i class="bi bi-exclamation-triangle"></i>
            يرجى تغيير كلمة المرور فور تسجيل الدخول
        </small>
    </div>

    <div class="next-steps mb-4">
        <h5 class="mb-3">الخطوات التالية:</h5>
        <div class="steps-grid">
            <div class="step-item">
                <i class="bi bi-box-arrow-in-right"></i>
                <h6>تسجيل الدخول</h6>
                <p>الدخول للوحة التحكم</p>
            </div>
            <div class="step-item">
                <i class="bi bi-gear"></i>
                <h6>الإعدادات</h6>
                <p>تخصيص إعدادات النظام</p>
            </div>
            <div class="step-item">
                <i class="bi bi-people"></i>
                <h6>المستخدمين</h6>
                <p>إدارة المستخدمين والصلاحيات</p>
            </div>
            <div class="step-item">
                <i class="bi bi-rocket"></i>
                <h6>البدء</h6>
                <p>بدء استخدام النظام</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/login" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right"></i>
            الذهاب لتسجيل الدخول
        </a>
    </div>
</div>
@endsection 