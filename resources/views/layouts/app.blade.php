<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('languages.available.' . app()->getLocale() . '.dir') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PhoenuxSys') }}</title>
    
    <!-- CSS الأساسي -->
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <!-- CSS حسب اتجاه اللغة -->
    @if(config('languages.available.' . app()->getLocale() . '.dir') === 'rtl')
        <link rel="stylesheet" href="/assets/css/rtl.css">
    @endif
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <!-- محدد اللغة -->
    <div class="language-switcher">
        @foreach(config('languages.available') as $lang)
            <a href="?lang={{ $lang['code'] }}" 
               class="lang-btn {{ app()->getLocale() === $lang['code'] ? 'active' : '' }}">
                {{ $lang['name'] }}
            </a>
        @endforeach
    </div>

    <div id="app">
        @include('layouts.navigation')
        <main class="py-4">
            @yield('content')
        </main>
        @include('layouts.footer')
    </div>

    @if(config('cookies.consent.enabled') && !Cookie::has('cookie_consent'))
    <div id="cookie-consent" class="fixed bottom-0 w-full bg-gray-900 text-white p-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-sm">
                {{ __('cookies.consent_message') }}
                <a href="{{ route('cookie.consent') }}" class="underline">
                    {{ __('cookies.learn_more') }}
                </a>
            </div>
            <div class="flex gap-2">
                <button onclick="acceptCookies()" class="btn btn-primary btn-sm">
                    {{ __('cookies.accept') }}
                </button>
                <button onclick="rejectCookies()" class="btn btn-secondary btn-sm">
                    {{ __('cookies.reject') }}
                </button>
            </div>
        </div>
    </div>

    <script>
    function acceptCookies() {
        fetch('{{ route("cookie.accept") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        }).then(() => {
            document.getElementById('cookie-consent').remove();
        });
    }

    function rejectCookies() {
        fetch('{{ route("cookie.reject") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            }
        }).then(() => {
            document.getElementById('cookie-consent').remove();
        });
    }
    </script>
    @endif
</body>
</html> 