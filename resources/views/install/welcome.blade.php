@extends('install.layout')

@section('content')
<div class="welcome-section text-center">
    <div class="icon-wrapper mb-4">
        <i class="fas fa-rocket fa-3x text-primary"></i>
    </div>
    
    <h2 class="mb-4">مرحباً بك في نظام Phoenix IT</h2>
    <p class="text-muted mb-5">سنساعدك في تثبيت النظام خطوة بخطوة</p>

    <div class="steps-overview mb-5">
        <div class="step">
            <div class="step-number">1</div>
            <div class="step-title">التحقق من المتطلبات</div>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <div class="step-title">إعداد قاعدة البيانات</div>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <div class="step-title">إعداد النظام</div>
        </div>
        <div class="step">
            <div class="step-number">4</div>
            <div class="step-title">إنشاء حساب المدير</div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('install.requirements') }}" class="btn btn-primary btn-lg">
            ابدأ التثبيت
            <i class="fas fa-arrow-left me-2"></i>
        </a>
    </div>
</div>

@push('styles')
<style>
.welcome-section {
    padding: 20px;
}

.steps-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 40px 0;
}

.step {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    position: relative;
}

.step-number {
    width: 30px;
    height: 30px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.step-title {
    font-weight: 600;
    color: var(--secondary-color);
}
</style>
@endpush 