@extends('layouts.app')

@section('content')
<div class="careers-section py-12">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-8">الوظائف الشاغرة</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($positions as $position)
            <div class="job-card bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold">{{ $position->title }}</h3>
                <p class="text-gray-600">{{ $position->department }}</p>
                <div class="mt-4">
                    {!! Str::limit($position->description, 150) !!}
                </div>
                <div class="mt-4">
                    <a href="{{ route('careers.show', $position) }}" 
                       class="btn btn-primary">
                        تفاصيل الوظيفة
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 