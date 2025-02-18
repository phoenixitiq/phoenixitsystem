@extends('layouts.app')

@section('content')
<div class="packages-section py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">باقات إدارة السوشيال ميديا</h2>
            <p class="text-xl text-gray-600">اختر الباقة المناسبة لنمو عملك</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($packages as $package)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-center mb-4">{{ $package->name }}</h3>
                    <div class="text-center mb-8">
                        <span class="text-4xl font-bold">{{ number_format($package->price) }}</span>
                        <span class="text-gray-600">ريال/شهرياً</span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>{{ $package->posts_per_month }} منشور شهرياً</span>
                        </div>

                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>{{ count($package->platforms) }} منصات تواصل اجتماعي</span>
                        </div>

                        @if($package->includes_strategy)
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>استراتيجية محتوى شهرية</span>
                        </div>
                        @endif

                        @if($package->includes_monitoring)
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>مراقبة وتحليل الأداء</span>
                        </div>
                        @endif

                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>وقت الاستجابة: {{ $package->response_time }}</span>
                        </div>

                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>تقارير {{ $package->reports_frequency }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50">
                    <a href="{{ route('subscriptions.create', $package) }}" 
                       class="block w-full text-center bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition">
                        اشترك الآن
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <h3 class="text-2xl font-bold mb-6">مميزات إضافية في جميع الباقات</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white rounded-lg shadow">
                    <i class="fas fa-camera text-3xl text-primary-600 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">تصوير احترافي</h4>
                    <p class="text-gray-600">جلسة تصوير شهرية للمحتوى</p>
                </div>
                
                <div class="p-6 bg-white rounded-lg shadow">
                    <i class="fas fa-paint-brush text-3xl text-primary-600 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">تصميم جرافيك</h4>
                    <p class="text-gray-600">تصاميم احترافية لجميع المنشورات</p>
                </div>

                <div class="p-6 bg-white rounded-lg shadow">
                    <i class="fas fa-chart-line text-3xl text-primary-600 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">تقارير تحليلية</h4>
                    <p class="text-gray-600">تقارير مفصلة عن أداء الحسابات</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 