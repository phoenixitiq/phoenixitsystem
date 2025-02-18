@extends('install.layout')

@section('content')
<div class="step-content">
    <h3 class="mb-4">إعداد قاعدة البيانات</h3>
    
    <form action="{{ route('install.database.setup') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">نوع قاعدة البيانات</label>
            <select name="db_connection" class="form-select" required>
                <option value="mysql" selected>MySQL</option>
                <option value="pgsql">PostgreSQL</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">اسم المضيف</label>
            <input type="text" name="db_host" class="form-control" value="localhost" required>
        </div>

        <div class="mb-3">
            <label class="form-label">المنفذ</label>
            <input type="number" name="db_port" class="form-control" value="3306" required>
        </div>

        <div class="mb-3">
            <label class="form-label">اسم قاعدة البيانات</label>
            <input type="text" name="db_database" class="form-control" value="phoenix_db" required>
        </div>

        <div class="mb-3">
            <label class="form-label">اسم المستخدم</label>
            <input type="text" name="db_username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">كلمة المرور</label>
            <input type="password" name="db_password" class="form-control">
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('install.requirements') }}" class="btn btn-secondary">السابق</a>
            <button type="submit" class="btn btn-primary">التالي</button>
        </div>
    </form>
</div>
@endsection 