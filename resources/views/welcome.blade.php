@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html dir="rtl" lang="ar">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Phoenix IT System - نظام إدارة الخدمات التقنية</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-color: #04a887;
            }
            body {
                background-color: #f8f9fa;
                font-family: system-ui, -apple-system, sans-serif;
            }
            .welcome-container {
                max-width: 1200px;
                margin: 2rem auto;
                padding: 2rem;
            }
            .welcome-content {
                background: white;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
                padding: 2rem;
            }
            .logo {
                height: 100px;
                width: auto;
                margin-bottom: 1rem;
            }
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin: 2rem 0;
            }
            .feature-card {
                padding: 1.5rem;
                background: #f8f9fa;
                border-radius: 8px;
                text-align: center;
                transition: transform 0.3s;
            }
            .feature-card:hover {
                transform: translateY(-5px);
            }
            .feature-icon {
                color: var(--primary-color);
                margin-bottom: 1rem;
            }
            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                padding: 0.5rem 2rem;
            }
            .btn-primary:hover {
                filter: brightness(90%);
            }
            .auth-buttons {
                margin-top: 2rem;
            }
        </style>
    </head>
    <body>
        <div class="welcome-container">
            <div class="welcome-content">
                <div class="text-center">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="Phoenix IT" class="logo">
                    <h1 class="mt-4">مرحباً بك في نظام Phoenix IT</h1>
                    <p class="text-muted">نظام متكامل لإدارة الخدمات التقنية</p>
                </div>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                        <h5>إدارة المشاريع</h5>
                        <p>إدارة فعالة للمشاريع والمهام</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h5>إدارة الفريق</h5>
                        <p>تنظيم وإدارة فريق العمل</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h5>التقارير والإحصائيات</h5>
                        <p>تحليل وعرض البيانات</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset fa-2x"></i>
                        </div>
                        <h5>الدعم الفني</h5>
                        <p>نظام متكامل لإدارة التذاكر</p>
                    </div>
                </div>

                <div class="text-center auth-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary mx-2">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            تسجيل الدخول
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary mx-2">
                                <i class="fas fa-user-plus me-2"></i>
                                حساب جديد
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
@endsection

@section('styles')
<style>
    /* CSS styles */
</style>
@endsection
