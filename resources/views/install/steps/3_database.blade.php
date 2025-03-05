@extends('install.layouts.master')

@section('content')
<div class="database-setup">
    <h3 class="mb-4">إعداد قاعدة البيانات</h3>
    
    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle"></i>
        يرجى إدخال معلومات الاتصال بقاعدة البيانات. إذا لم تكن متأكداً من هذه المعلومات، يرجى الاتصال بمزود الخدمة.
    </div>

    <form id="database-form" action="{{ route('install.database.setup') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="db_host" class="form-label">خادم قاعدة البيانات</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-hdd-network"></i></span>
                    <input type="text" class="form-control @error('db_host') is-invalid @enderror" 
                           id="db_host" name="db_host" value="{{ old('db_host', 'localhost') }}"
                           placeholder="مثال: localhost">
                </div>
                @error('db_host')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="db_port" class="form-label">المنفذ</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-diagram-2"></i></span>
                    <input type="number" class="form-control @error('db_port') is-invalid @enderror" 
                           id="db_port" name="db_port" value="{{ old('db_port', '3306') }}"
                           placeholder="مثال: 3306">
                </div>
                @error('db_port')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="db_database" class="form-label">اسم قاعدة البيانات</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-database"></i></span>
                <input type="text" class="form-control @error('db_database') is-invalid @enderror" 
                       id="db_database" name="db_database" value="{{ old('db_database') }}"
                       placeholder="أدخل اسم قاعدة البيانات">
            </div>
            @error('db_database')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="db_username" class="form-label">اسم المستخدم</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control @error('db_username') is-invalid @enderror" 
                       id="db_username" name="db_username" value="{{ old('db_username') }}"
                       placeholder="أدخل اسم مستخدم قاعدة البيانات">
            </div>
            @error('db_username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="db_password" class="form-label">كلمة المرور</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="password" class="form-control @error('db_password') is-invalid @enderror" 
                       id="db_password" name="db_password"
                       placeholder="أدخل كلمة مرور قاعدة البيانات">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('db_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('install.requirements') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> السابق
            </a>
            <button type="submit" class="btn btn-primary">
                التالي <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('db_password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});
</script>
@endpush
@endsection 