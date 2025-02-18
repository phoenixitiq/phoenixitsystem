@extends('install.layout')

@section('content')
<div class="step-content text-center">
    <div class="success-icon mb-4">
        <i class="fas fa-check-circle fa-4x text-success"></i>
    </div>
    
    <h3 class="mb-4">تم تثبيت النظام بنجاح!</h3>
    
    <p class="mb-4">
        يمكنك الآن تسجيل الدخول باستخدام بيانات الاعتماد التالية:
    </p>
    
    <div class="credentials-box mb-4 p-3 bg-light rounded">
        <p class="mb-2"><strong>البريد الإلكتروني:</strong> admin@phoenixitiq.com</p>
        <p class="mb-0"><strong>كلمة المرور:</strong> admin123</p>
    </div>
    
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        يرجى تغيير كلمة المرور فور تسجيل الدخول
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
            انتقل إلى لوحة التحكم
            <i class="fas fa-arrow-left me-2"></i>
        </a>
    </div>
</div>
@endsection 