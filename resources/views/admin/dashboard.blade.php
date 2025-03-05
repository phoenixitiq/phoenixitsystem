@extends('layouts.admin')

@section('content')
<div class="dashboard-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="welcome-message">
                    <h1>مرحباً {{ auth()->user()->name }}</h1>
                    <p>{{ now()->format('l d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-stats">
    <div class="container-fluid">
        <div class="row">
            <!-- إحصائيات سريعة -->
            <div class="col-md-3">
                <div class="stat-card bg-primary">
                    <div class="stat-card-body">
                        <h2>{{ $totalEmployees }}</h2>
                        <p>الموظفين</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-success">
                    <div class="stat-card-body">
                        <h2>{{ $presentToday }}</h2>
                        <p>الحضور اليوم</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-warning">
                    <div class="stat-card-body">
                        <h2>{{ $pendingLeaves }}</h2>
                        <p>طلبات الإجازة</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-info">
                    <div class="stat-card-body">
                        <h2>{{ $totalTasks }}</h2>
                        <p>المهام النشطة</p>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- الرسوم البيانية -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>إحصائيات الحضور الشهرية</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>المصروفات حسب الفئة</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expensesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- آخر النشاطات -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>آخر النشاطات</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-content">
                                    <div class="activity-icon bg-{{ $activity->type_color }}">
                                        <i class="fas fa-{{ $activity->icon }}"></i>
                                    </div>
                                    <div class="activity-info">
                                        <p class="activity-text">{{ $activity->description }}</p>
                                        <p class="activity-time">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- المهام العاجلة -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>المهام العاجلة</h5>
                    </div>
                    <div class="card-body">
                        <div class="urgent-tasks">
                            @foreach($urgentTasks as $task)
                            <div class="task-item">
                                <div class="task-status">
                                    <span class="badge badge-{{ $task->status_color }}">
                                        {{ $task->status_text }}
                                    </span>
                                </div>
                                <div class="task-content">
                                    <h6>{{ $task->title }}</h6>
                                    <p>{{ $task->description }}</p>
                                    <div class="task-meta">
                                        <span><i class="far fa-clock"></i> {{ $task->due_date->format('d/m/Y') }}</span>
                                        <span><i class="far fa-user"></i> {{ $task->assigned_to->name }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.dashboard-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    padding: 20px 0;
    margin-bottom: 30px;
}

.stat-card {
    border-radius: 10px;
    color: white;
    padding: 20px;
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
}

.stat-card-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 3rem;
    opacity: 0.3;
}

.activity-timeline {
    position: relative;
}

.activity-item {
    padding: 15px 0;
    border-left: 2px solid #e9ecef;
    margin-left: 30px;
    position: relative;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    position: absolute;
    left: -20px;
}

.task-item {
    border-bottom: 1px solid #e9ecef;
    padding: 15px 0;
}

.task-item:last-child {
    border-bottom: none;
}

.task-meta {
    font-size: 0.8rem;
    color: #6c757d;
}

.task-meta span {
    margin-right: 15px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني للحضور
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
new Chart(attendanceCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($attendanceData->pluck('date')) !!},
        datasets: [{
            label: 'نسبة الحضور',
            data: {!! json_encode($attendanceData->pluck('percentage')) !!},
            borderColor: '#4e73df',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

// رسم بياني للمصروفات
const expensesCtx = document.getElementById('expensesChart').getContext('2d');
new Chart(expensesCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($expensesData->pluck('category')) !!},
        datasets: [{
            data: {!! json_encode($expensesData->pluck('amount')) !!},
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>
@endpush 