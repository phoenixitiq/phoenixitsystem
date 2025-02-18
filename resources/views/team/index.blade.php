@extends('layouts.app')

@section('content')
<div class="team-section py-12">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-8">فريق العمل</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($teamMembers as $member)
            <div class="team-member">
                <img src="{{ $member->image }}" alt="{{ $member->user->name }}" class="rounded-lg mb-4">
                <h3 class="text-xl font-semibold">{{ $member->user->name }}</h3>
                <p class="text-gray-600">{{ $member->position }}</p>
                <p class="mt-2">{{ $member->bio }}</p>
                <div class="social-links mt-4">
                    @foreach($member->social_links as $platform => $link)
                    <a href="{{ $link }}" target="_blank" class="mr-3">
                        <i class="fab fa-{{ $platform }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 