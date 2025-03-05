@php
$steps = [
    1 => [
        'title' => 'الترحيب',
        'route' => 'install.welcome',
        'icon' => 'bi-house-door'
    ],
    2 => [
        'title' => 'المتطلبات',
        'route' => 'install.requirements',
        'icon' => 'bi-check-circle'
    ],
    3 => [
        'title' => 'قاعدة البيانات',
        'route' => 'install.database',
        'icon' => 'bi-database'
    ],
    4 => [
        'title' => 'اكتمال',
        'route' => 'install.complete',
        'icon' => 'bi-flag'
    ]
];

$currentStep = request()->route()->getName();
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'تثبيت النظام' }} - Phoenix IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('install/assets/css/style.css') }}" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .install-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .step {
            text-align: center;
            flex: 1;
            position: relative;
        }
        .step.active .step-number {
            background: #0d6efd;
            color: white;
        }
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Phoenix IT" height="60">
            </div>

            <!-- مؤشر الخطوات -->
            <div class="step-indicator mb-5">
                @foreach($steps as $number => $step)
                    <div class="step {{ $currentStep == $step['route'] ? 'active' : '' }}">
                        <div class="step-number">
                            <i class="bi {{ $step['icon'] }}"></i>
                        </div>
                        <div class="step-title">{{ $step['title'] }}</div>
                        @if($number < count($steps))
                            <div class="step-line"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('install/assets/js/install.js') }}"></script>
    @stack('scripts')
</body>
</html> 