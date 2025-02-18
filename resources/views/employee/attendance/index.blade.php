@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تسجيل الحضور والانصراف</div>

                <div class="card-body">
                    <!-- عرض وقت الحضور اليوم إن وجد -->
                    @if($todayAttendance)
                        <div class="alert alert-info">
                            <p>وقت الحضور: {{ $todayAttendance->check_in }}</p>
                            @if($todayAttendance->check_out)
                                <p>وقت الانصراف: {{ $todayAttendance->check_out }}</p>
                                <p>ساعات العمل: {{ $todayAttendance->work_hours }}</p>
                            @else
                                <form action="{{ route('attendance.check-out') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">تسجيل انصراف</button>
                                </form>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('attendance.check-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">تسجيل حضور</button>
                        </form>
                    @endif

                    <!-- زر طلب إجازة -->
                    <a href="{{ route('leaves.create') }}" class="btn btn-success mt-3">
                        طلب إجازة
                    </a>

                    <!-- زر طلب إذن خروج -->
                    <a href="{{ route('permissions.create') }}" class="btn btn-info mt-3">
                        طلب إذن خروج
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 