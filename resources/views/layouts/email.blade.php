<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #eee;
        }
        .email-content {
            padding: 20px 0;
        }
        .email-footer {
            text-align: center;
            padding: 20px 0;
            border-top: 2px solid #eee;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="50">
        </div>

        <div class="email-content">
            @yield('content')
        </div>

        <div class="email-footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة</p>
            <p>هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه</p>
        </div>
    </div>
</body>
</html> 