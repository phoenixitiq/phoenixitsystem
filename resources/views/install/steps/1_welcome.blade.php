@extends('install.layouts.master')

@section('content')
<div class="text-center">
    <h2 class="mb-4">مرحباً بك في نظام Phoenix IT</h2>
    <p class="lead mb-4">سنقوم بمساعدتك في تثبيت النظام خلال خطوات بسيطة</p>
    
    <div class="mb-4">
        <h5>قبل البدء تأكد من:</h5>
        <ul class="list-unstyled">
            <li><i class="bi bi-check-circle text-success"></i> توفر متطلبات النظام</li>
            <li><i class="bi bi-check-circle text-success"></i> معلومات قاعدة البيانات</li>
            <li><i class="bi bi-check-circle text-success"></i> صلاحيات المجلدات</li>
        </ul>
    </div>

    <a href="{{ route('install.requirements') }}" class="btn btn-primary btn-lg">
        البدء بالتثبيت <i class="bi bi-arrow-right"></i>
    </a>
</div>
@endsection 