@extends('admin.layout')

@section('content')
<div class="dashboard-container">
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>{{ $usersCount }}</h3>
                <p>المستخدمين</p>
            </div>
        </div>
        <!-- إضافة المزيد من الإحصائيات -->
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>آخر النشاطات</h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @foreach($activities as $activity)
                        <div class="activity-item">
                            <span class="time">{{ $activity->created_at->diffForHumans() }}</span>
                            <p>{{ $activity->description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>حالة النظام</h5>
                </div>
                <div class="card-body">
                    <ul class="system-status">
                        <li>
                            <span>حالة المزامنة</span>
                            <span class="badge bg-success">متصل</span>
                        </li>
                        <li>
                            <span>آخر نسخة احتياطية</span>
                            <span>{{ $lastBackup }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 