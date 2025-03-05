@extends('layouts.app')

@section('content')
<div class="job-details py-12">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-6">{{ $position->title }}</h2>
        
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-3">الوصف الوظيفي</h3>
                {!! $position->description !!}
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-3">المتطلبات</h3>
                {!! $position->requirements !!}
            </div>

            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4">التقديم على الوظيفة</h3>
                <form action="{{ route('careers.apply', $position) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    <!-- داخل form -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="name" class="block mb-2">الاسم الكامل</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="block mb-2">البريد الإلكتروني</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="block mb-2">رقم الهاتف</label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="cv" class="block mb-2">السيرة الذاتية</label>
                            <input type="file" 
                                   name="cv" 
                                   id="cv" 
                                   class="form-control"
                                   accept=".pdf,.doc,.docx"
                                   required>
                            <small class="text-gray-500">PDF, DOC, DOCX (الحد الأقصى 2MB)</small>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">
                            تقديم الطلب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 