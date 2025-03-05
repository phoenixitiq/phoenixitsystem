@extends('install.layouts.master')

@section('content')
<h3 class="mb-4">متطلبات النظام</h3>

<div class="requirements mb-4">
    @foreach($requirements as $type => $items)
        <h5 class="mt-4">{{ $type }}</h5>
        <div class="list-group">
            @foreach($items as $requirement => $met)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $requirement }}
                    @if($met)
                        <i class="bi bi-check-circle text-success"></i>
                    @else
                        <i class="bi bi-x-circle text-danger"></i>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('install.welcome') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> السابق
    </a>
    @if($canProceed)
        <a href="{{ route('install.database') }}" class="btn btn-primary">
            التالي <i class="bi bi-arrow-right"></i>
        </a>
    @else
        <button class="btn btn-primary" disabled>
            يرجى استيفاء المتطلبات
        </button>
    @endif
</div>
@endsection 