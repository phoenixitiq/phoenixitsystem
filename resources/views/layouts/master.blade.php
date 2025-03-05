<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <!-- نحافظ على نفس ملفات CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- نضيف TailwindCSS كما هو في النظام الأصلي -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    @include('layouts.partials.navigation')
    <main>
        @yield('content')
    </main>
    @include('components.footer')
</body>
</html> 