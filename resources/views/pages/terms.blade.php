@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-6">{{ __('terms.title') }}</h1>
    <div class="prose max-w-none">
        {!! __('terms.content') !!}
    </div>
</div>
@endsection 