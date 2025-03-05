@extends('layouts.app')

@section('content')
<div class="cookie-consent-page">
    <div class="container py-8">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">{{ __('cookies.policy_title') }}</h1>
            
            <div class="bg-white rounded-lg shadow p-6">
                <p class="mb-4">{{ __('cookies.policy_description') }}</p>
                
                <div class="space-y-4">
                    <div class="cookie-type">
                        <h3 class="font-semibold">الكوكيز الضرورية</h3>
                        <p>هذه الكوكيز ضرورية لعمل الموقع بشكل صحيح</p>
                    </div>
                    
                    <div class="cookie-type">
                        <h3 class="font-semibold">كوكيز التحليلات</h3>
                        <p>تساعدنا في فهم كيفية استخدام الزوار لموقعنا</p>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <button onclick="acceptCookies()" class="btn btn-primary">
                        {{ __('cookies.accept') }}
                    </button>
                    <button onclick="rejectCookies()" class="btn btn-secondary">
                        {{ __('cookies.essential_only') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 