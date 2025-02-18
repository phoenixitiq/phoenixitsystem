@extends('install.layout')

@section('content')
<div class="step-content text-center">
    <div class="welcome-icon mb-4">
        <i class="fas fa-rocket fa-4x text-primary"></i>
    </div>
    
    <h3 class="mb-4">مرحباً بك في معالج تثبيت Phoenix IT</h3>
    
    <p class="mb-4">
        سنقوم بمساعدتك في إعداد النظام خطوة بخطوة. تأكد من توفر المتطلبات التالية:
    </p>
    
    <ul class="list-unstyled text-start mb-4">
        <li><i class="fas fa-check text-success me-2"></i> PHP 8.1 أو أعلى</li>
        <li><i class="fas fa-check text-success me-2"></i> خادم قواعد بيانات MySQL</li>
        <li><i class="fas fa-check text-success me-2"></i> خادم بريد SMTP</li>
        <li><i class="fas fa-check text-success me-2"></i> صلاحيات كتابة للمجلدات</li>
    </ul>
    
    <div class="d-flex justify-content-center mt-4">
        <a href="{{ route('install.requirements') }}" class="btn btn-primary btn-lg">
            ابدأ التثبيت
            <i class="fas fa-arrow-left me-2"></i>
        </a>
    </div>
</div>
@endsection 