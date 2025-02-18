@extends('install.layout')

@section('content')
<div class="welcome-screen text-center">
    <div class="welcome-icon mb-4">
        <i class="fas fa-rocket fa-4x text-primary"></i>
    </div>
    
    <h1 class="mb-4">مرحباً بك في نظام Phoenix IT</h1>
    <p class="lead mb-4">سنساعدك في تثبيت النظام خطوة بخطوة</p>
    
    <div class="features-grid mb-5">
        <div class="feature-item">
            <i class="fas fa-sync-alt"></i>
            <h3>تحديثات مستمرة</h3>
            <p>تحديثات دورية للنظام</p>
        </div>
        
        <div class="feature-item">
            <i class="fas fa-shield-alt"></i>
            <h3>آمن</h3>
            <p>حماية متقدمة لبياناتك</p>
        </div>
        
        <div class="feature-item">
            <i class="fas fa-check-circle"></i>
            <h3>سهل الاستخدام</h3>
            <p>واجهة بسيطة وسهلة الاستخدام</p>
        </div>
    </div>

    <a href="?step=1" class="btn btn-primary btn-lg">
        <i class="fas fa-arrow-right ml-2"></i>
        ابدأ التثبيت
    </a>
</div>

<style>
.welcome-screen {
    padding: 2rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.feature-item {
    padding: 1.5rem;
    border-radius: 10px;
    background: #f8f9fa;
    transition: transform 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
}

.feature-item i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.feature-item h3 {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.feature-item p {
    color: #6c757d;
    margin: 0;
}
</style>
@endsection 