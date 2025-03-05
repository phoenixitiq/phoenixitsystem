@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- قسم العنوان -->
    <section class="relative py-20 overflow-hidden bg-emerald-900">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4">
            <div class="text-center">
                <div class="inline-block bg-emerald-800/50 p-4 rounded-2xl backdrop-blur-sm mb-6">
                    <i class="fas fa-users text-4xl text-emerald-200"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    {{ __('فريق العمل') }}
                </h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto">
                    {{ __('نفخر بفريقنا المتميز الذي يجمع بين الخبرة والإبداع لتقديم أفضل الحلول لعملائنا') }}
                </p>
            </div>
        </div>
    </section>

    <!-- قسم تصفية الأقسام -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4">
                @foreach($departments as $department)
                <button
                    data-department="{{ $department->id }}"
                    class="department-filter flex items-center gap-2 px-6 py-3 rounded-full transition-all
                        {{ request('department') == $department->id ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}"
                >
                    <span>{{ app()->getLocale() == 'ar' ? $department->display_name_ar : $department->display_name_en }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- قسم عرض الفريق -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($employees as $employee)
                <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300">
                    <!-- صورة الموظف -->
                    <div class="aspect-square bg-gradient-to-br from-emerald-50 to-white p-8">
                        <img 
                            src="{{ asset('storage/' . $employee->image) }}" 
                            alt="{{ app()->getLocale() == 'ar' ? $employee->name_ar : $employee->name_en }}"
                            class="w-full h-full object-contain transform group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>

                    <!-- معلومات الموظف عند التحويم -->
                    <div class="absolute inset-0 bg-gradient-to-t from-emerald-900 via-emerald-900/70 to-transparent opacity-0 group-hover:opacity-90 transition-opacity duration-300">
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                            <h3 class="text-xl font-bold mb-2">
                                {{ app()->getLocale() == 'ar' ? $employee->name_ar : $employee->name_en }}
                            </h3>
                            <p class="text-emerald-200 mb-4">
                                {{ app()->getLocale() == 'ar' ? $employee->role_ar : $employee->role_en }}
                            </p>
                            <p class="text-sm text-gray-100 leading-relaxed mb-4">
                                {{ app()->getLocale() == 'ar' ? $employee->bio_ar : $employee->bio_en }}
                            </p>

                            <!-- روابط التواصل الاجتماعي -->
                            <div class="flex gap-3">
                                @if($employee->email)
                                <a href="mailto:{{ $employee->email }}" 
                                   class="text-white hover:text-emerald-200 transition-colors"
                                   title="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                @endif

                                @if($employee->social_links)
                                    @foreach(json_decode($employee->social_links) as $platform => $username)
                                    <a href="{{ getSocialLink($platform, $username) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-white hover:text-emerald-200 transition-colors"
                                       title="{{ ucfirst($platform) }}">
                                        <i class="fab fa-{{ $platform }}"></i>
                                    </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- معلومات الموظف الأساسية -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ app()->getLocale() == 'ar' ? $employee->name_ar : $employee->name_en }}
                        </h3>
                        <p class="text-emerald-600">
                            {{ app()->getLocale() == 'ar' ? $employee->role_ar : $employee->role_en }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- قسم الانضمام للفريق -->
    <section class="py-20 bg-emerald-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">
                {{ __('انضم إلى فريقنا') }}
            </h2>
            <p class="text-xl text-emerald-100 mb-8 max-w-2xl mx-auto">
                {{ __('نحن دائماً نبحث عن المواهب المتميزة للانضمام إلى فريقنا. إذا كنت تشاركنا شغفنا بالابتكار والتميز، نرحب بك للانضمام إلينا') }}
            </p>
            <a href="mailto:careers@phoenixitiq.com"
               class="inline-flex items-center bg-white text-emerald-900 px-8 py-4 rounded-full hover:bg-emerald-50 transition-colors text-lg font-medium">
                {{ __('أرسل سيرتك الذاتية') }}
                <i class="fas fa-envelope ml-2"></i>
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.department-filter').forEach(button => {
    button.addEventListener('click', function() {
        const department = this.dataset.department;
        window.location.href = `{{ route('team.index') }}?department=${department}`;
    });
});
</script>
@endpush 