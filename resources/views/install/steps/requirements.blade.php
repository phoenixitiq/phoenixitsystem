@extends('install.layout')

@section('content')
<div class="step-content">
    <h3 class="mb-4">متطلبات النظام</h3>
    
    <div class="requirements-list">
        @foreach($requirements as $type => $items)
            <h4>{{ $type === 'php' ? 'إصدار PHP' : ($type === 'extensions' ? 'الامتدادات المطلوبة' : 'الصلاحيات') }}</h4>
            <ul class="list-group mb-4">
                @foreach($items as $item => $status)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item }}
                        @if($status)
                            <span class="badge bg-success rounded-pill">✓</span>
                        @else
                            <span class="badge bg-danger rounded-pill">✗</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-between mt-4">
        <button class="btn btn-secondary" disabled>السابق</button>
        <form action="{{ route('install.database') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" {{ $canProceed ? '' : 'disabled' }}>التالي</button>
        </form>
    </div>
</div>
@endsection 