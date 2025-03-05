<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'XB Riyaz';
            src: url({{ storage_path('fonts/XBRiyaz.ttf') }}) format('truetype');
        }
        body {
            font-family: 'XB Riyaz', sans-serif;
            color: {{ $colors['text'] }};
            line-height: 1.6;
        }
        .header {
            padding: 20px;
            border-bottom: 2px solid {{ $colors['primary'] }};
        }
        .logo {
            width: 150px;
            height: auto;
        }
        .company-info {
            text-align: left;
            color: {{ $colors['primary'] }};
        }
        .content {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: {{ $colors['primary'] }};
            color: white;
            padding: 10px;
            text-align: right;
        }
        td {
            padding: 8px;
            border: 1px solid {{ $colors['text'] }};
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: {{ $colors['text'] }};
            border-top: 1px solid {{ $colors['primary'] }};
        }
        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%">
            <tr>
                <td style="width: 30%; border: none;">
                    <img src="{{ $company['logo'] }}" class="logo">
                </td>
                <td style="width: 70%; border: none;" class="company-info">
                    <h2>{{ $company['name'] }}</h2>
                    <p>{{ $company['address'] }}</p>
                    <p>{{ $company['phone'] }} | {{ $company['email'] }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        <p>{{ $company['name'] }} © {{ date('Y') }} | صفحة <span class="page-number"></span></p>
    </div>
</body>
</html> 