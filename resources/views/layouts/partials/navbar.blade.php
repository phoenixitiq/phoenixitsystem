<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-emerald-600">
                    Phoenix
                </a>
            </div>

            <!-- القائمة الرئيسية -->
            <div class="hidden md:flex items-center space-x-8 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    {{ __('menu.home') }}
                </a>
                <a href="{{ route('services') }}" class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}">
                    {{ __('menu.services') }}
                </a>
                <a href="{{ route('packages') }}" class="nav-link {{ request()->routeIs('packages') ? 'active' : '' }}">
                    {{ __('menu.packages') }}
                </a>
                <a href="{{ route('team') }}" class="nav-link {{ request()->routeIs('team') ? 'active' : '' }}">
                    {{ __('menu.team') }}
                </a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    {{ __('menu.contact') }}
                </a>

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center">
                            <span class="mr-2">{{ auth()->user()->name }}</span>
                            <img src="{{ auth()->user()->avatar }}" class="h-8 w-8 rounded-full">
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                                {{ __('menu.dashboard') }}
                            </a>
                            <a href="{{ route('chat') }}" class="block px-4 py-2 hover:bg-gray-100">
                                {{ __('menu.chat') }}
                                @if($unreadMessages > 0)
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">
                                        {{ $unreadMessages }}
                                    </span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-right px-4 py-2 hover:bg-gray-100">
                                    {{ __('menu.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">
                        {{ __('menu.login') }}
                    </a>
                @endauth

                <!-- تبديل اللغة -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center">
                        <span class="flag-icon flag-icon-{{ app()->getLocale() === 'ar' ? 'sa' : 'gb' }}"></span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
                        <a href="{{ route('language.switch', 'ar') }}" class="block px-4 py-2 hover:bg-gray-100">
                            العربية
                        </a>
                        <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 hover:bg-gray-100">
                            English
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav> 