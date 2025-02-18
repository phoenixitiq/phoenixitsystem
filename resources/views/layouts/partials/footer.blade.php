<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- معلومات الشركة -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ __('footer.about_us') }}</h3>
                <p class="mb-4">{{ __('footer.company_description') }}</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
            </div>

            <!-- روابط سريعة -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ __('footer.quick_links') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}">{{ __('footer.about') }}</a></li>
                    <li><a href="{{ route('services') }}">{{ __('footer.services') }}</a></li>
                    <li><a href="{{ route('packages') }}">{{ __('footer.packages') }}</a></li>
                    <li><a href="{{ route('team') }}">{{ __('footer.team') }}</a></li>
                    <li><a href="{{ route('contact') }}">{{ __('footer.contact') }}</a></li>
                </ul>
            </div>

            <!-- خدماتنا -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ __('footer.our_services') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('services.web-development') }}">{{ __('footer.web_development') }}</a></li>
                    <li><a href="{{ route('services.mobile-apps') }}">{{ __('footer.mobile_apps') }}</a></li>
                    <li><a href="{{ route('services.digital-marketing') }}">{{ __('footer.digital_marketing') }}</a></li>
                    <li><a href="{{ route('services.graphic-design') }}">{{ __('footer.graphic_design') }}</a></li>
                    <li><a href="{{ route('services.hosting') }}">{{ __('footer.hosting') }}</a></li>
                </ul>
            </div>

            <!-- معلومات الاتصال -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ __('footer.contact_info') }}</h3>
                <ul class="space-y-2">
                    <li>
                        <i class="fas fa-phone mr-2"></i>
                        <span dir="ltr">+964 780 053 3950</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope mr-2"></i>
                        info@phoenixitiq.com
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ __('footer.address') }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- الفوتر السفلي -->
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="flex flex-wrap justify-between items-center">
                <div class="text-sm">
                    © {{ date('Y') }} Phoenix IT Solutions. {{ __('footer.all_rights_reserved') }}
                </div>
                <div class="flex space-x-4 text-sm">
                    <a href="{{ route('privacy-policy') }}">{{ __('footer.privacy_policy') }}</a>
                    <a href="{{ route('terms') }}">{{ __('footer.terms') }}</a>
                    <a href="{{ route('chat') }}">{{ __('footer.live_chat') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer> 