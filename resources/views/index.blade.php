@extends('layouts.app')

@section('content')
<div class="home-container">
    <div class="hero-section">
        <h1>{{ config('app.name') }}</h1>
        <p>{{ setting('site_description') }}</p>
    </div>

    <div class="services-section">
        <h2>خدماتنا</h2>
        <div class="row">
            @foreach($services as $service)
            <div class="col-md-4">
                <div class="service-card">
                    <img src="{{ $service->image }}" alt="{{ $service->name }}">
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="features-section">
        <!-- إضافة مميزات النظام -->
    </div>
</div>
@endsection 