@extends('install.layout')

@section('content')
<div class="requirements-section">
    <div class="requirements-group mb-4">
        <h3 class="mb-3">متطلبات PHP</h3>
        <div class="requirement-items">
            <div class="requirement-item {{ $requirements['php']['version'] ? 'success' : 'error' }}">
                <div class="requirement-info">
                    <span class="requirement-label">إصدار PHP</span>
                    <span class="requirement-value">{{ PHP_VERSION }}</span>
                </div>
                <div class="requirement-status">
                    @if($requirements['php']['version'])
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="requirements-group mb-4">
        <h3 class="mb-3">إضافات PHP المطلوبة</h3>
        <div class="requirement-items">
            @foreach($requirements['extensions'] as $extension => $installed)
            <div class="requirement-item {{ $installed ? 'success' : 'error' }}">
                <div class="requirement-info">
                    <span class="requirement-label">{{ $extension }}</span>
                </div>
                <div class="requirement-status">
                    @if($installed)
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="requirements-group mb-4">
        <h3 class="mb-3">صلاحيات المجلدات</h3>
        <div class="requirement-items">
            @foreach($requirements['permissions'] as $path => $isWritable)
            <div class="requirement-item {{ $isWritable ? 'success' : 'error' }}">
                <div class="requirement-info">
                    <span class="requirement-label">{{ $path }}</span>
                </div>
                <div class="requirement-status">
                    @if($isWritable)
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="action-buttons text-center mt-5">
        @if($canProceed)
            <a href="{{ route('install.database') }}" class="btn btn-primary">
                متابعة
                <i class="fas fa-arrow-left me-2"></i>
            </a>
        @else
            <div class="alert alert-danger">
                يرجى تلبية جميع المتطلبات قبل المتابعة
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.requirements-group {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.requirement-items {
    display: grid;
    gap: 10px;
}

.requirement-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.requirement-info {
    display: flex;
    gap: 20px;
}

.requirement-label {
    font-weight: 500;
}

.requirement-value {
    color: var(--secondary-color);
}

.requirement-item.success {
    border-right: 4px solid var(--success-color);
}

.requirement-item.error {
    border-right: 4px solid var(--error-color);
}
</style>
@endpush 