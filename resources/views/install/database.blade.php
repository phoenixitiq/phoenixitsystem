@extends('install.layout')

@section('content')
<div class="database-section">
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        يرجى إدخال معلومات الاتصال بقاعدة البيانات
    </div>

    <form action="{{ route('install.database.setup') }}" method="POST" class="database-form">
        @csrf
        
        <div class="form-group mb-3">
            <label for="db_host" class="form-label">خادم قاعدة البيانات</label>
            <input type="text" class="form-control" id="db_host" name="db_host" 
                   value="{{ old('db_host', 'localhost') }}" required>
            <div class="form-text">عادةً ما يكون "localhost"</div>
        </div>

        <div class="form-group mb-3">
            <label for="db_port" class="form-label">المنفذ</label>
            <input type="text" class="form-control" id="db_port" name="db_port" 
                   value="{{ old('db_port', '3306') }}" required>
            <div class="form-text">المنفذ الافتراضي هو 3306</div>
        </div>

        <div class="form-group mb-3">
            <label for="db_database" class="form-label">اسم قاعدة البيانات</label>
            <input type="text" class="form-control" id="db_database" name="db_database" 
                   value="{{ old('db_database') }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="db_username" class="form-label">اسم المستخدم</label>
            <input type="text" class="form-control" id="db_username" name="db_username" 
                   value="{{ old('db_username') }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="db_password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" id="db_password" name="db_password">
        </div>

        <div class="action-buttons text-center mt-5">
            <button type="submit" class="btn btn-primary">
                اختبار الاتصال والمتابعة
                <i class="fas fa-arrow-left me-2"></i>
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
.database-section {
    max-width: 600px;
    margin: 0 auto;
}

.database-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.form-group label {
    font-weight: 500;
    margin-bottom: 8px;
}

.form-text {
    font-size: 0.875rem;
    color: var(--secondary-color);
}
</style>
@endpush 