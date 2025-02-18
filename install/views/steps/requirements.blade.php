@extends('install.layout')

@section('content')
<div class="requirements-check">
    <h2 class="mb-4">فحص متطلبات النظام</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="requirements-section mb-4">
        <h3>متطلبات PHP</h3>
        <div class="requirements-grid">
            @foreach($requirements['php'] as $requirement)
            <div class="requirement-item {{ $requirement['status'] ? 'success' : 'error' }}">
                <span class="requirement-name">{{ $requirement['name'] }}</span>
                <span class="requirement-status">
                    @if($requirement['status'])
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="requirements-section mb-4">
        <h3>صلاحيات المجلدات</h3>
        <div class="requirements-grid">
            @foreach($requirements['permissions'] as $folder => $writable)
            <div class="requirement-item {{ $writable ? 'success' : 'error' }}">
                <span class="requirement-name">{{ $folder }}</span>
                <span class="requirement-status">
                    @if($writable)
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="?step=0" class="btn btn-secondary">
            <i class="fas fa-arrow-left ml-2"></i>
            السابق
        </a>
        @if($canProceed)
        <a href="?step=2" class="btn btn-primary">
            التالي
            <i class="fas fa-arrow-right mr-2"></i>
        </a>
        @else
        <button class="btn btn-primary" disabled>
            التالي
            <i class="fas fa-arrow-right mr-2"></i>
        </button>
        @endif
    </div>
</div>

<style>
.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}

.requirement-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-radius: 8px;
    background: #f8f9fa;
}

.requirement-item.success {
    border-left: 4px solid var(--success-color);
}

.requirement-item.error {
    border-left: 4px solid var(--error-color);
}
</style>
@endsection 