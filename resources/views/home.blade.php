@extends('layouts.app')

@section('content')
<!-- قسم الرئيسي -->
<div class="hero-section" style="background-image: url('/images/hero-bg.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="hero-title">{{ config('company.name') }}</h1>
                <p class="hero-subtitle">{{ config('company.slogan') }}</p>
                <div class="hero-buttons">
                    <a href="{{ route('contact') }}" class="btn btn-primary">تواصل معنا</a>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-light">خدماتنا</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- قسم الإحصائيات -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="stat-item">
                    <h3>{{ config('company.stats.clients') }}</h3>
                    <p>عميل سعيد</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h3>{{ config('company.stats.projects') }}</h3>
                    <p>مشروع منجز</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h3>{{ config('company.stats.experience') }}</h3>
                    <p>سنوات خبرة</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h3>{{ config('company.stats.team') }}</h3>
                    <p>خبير محترف</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- قسم الخدمات -->
<section class="services-section bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">خدماتنا</h2>
        <div class="row">
            @foreach($services as $service)
            <div class="col-md-4 mb-4">
                <div class="service-card">
                    <div class="icon">
                        <i class="{{ $service->icon }}"></i>
                    </div>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" class="btn btn-link">المزيد</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم المشاريع -->
<section class="projects-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">أحدث مشاريعنا</h2>
        <div class="row">
            @foreach($latestProjects as $project)
            <div class="col-md-4 mb-4">
                <div class="project-card">
                    <img src="{{ $project->image }}" alt="{{ $project->title }}">
                    <div class="project-info">
                        <h4>{{ $project->title }}</h4>
                        <p>{{ $project->description }}</p>
                        <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-primary btn-sm">التفاصيل</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم العملاء -->
<section class="clients-section bg-light py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">عملاؤنا</h2>
        <div class="clients-slider">
            @foreach($clients as $client)
            <div class="client-item">
                <img src="{{ $client->logo }}" alt="{{ $client->name }}">
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم الشهادات -->
<section class="testimonials-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">آراء العملاء</h2>
        <div class="testimonials-slider">
            @foreach($testimonials as $testimonial)
            <div class="testimonial-item">
                <div class="testimonial-content">
                    <p>{{ $testimonial->content }}</p>
                    <div class="client-info">
                        <img src="{{ $testimonial->image }}" alt="{{ $testimonial->name }}">
                        <h4>{{ $testimonial->name }}</h4>
                        <p>{{ $testimonial->position }} - {{ $testimonial->company }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم التواصل -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>تواصل معنا</h2>
                <p>نحن هنا لمساعدتك في تحقيق أهدافك الرقمية</p>
                <div class="contact-info">
                    <p><i class="fas fa-phone"></i> {{ config('company.phone') }}</p>
                    <p><i class="fas fa-envelope"></i> {{ config('company.email') }}</p>
                    <p><i class="fas fa-map-marker-alt"></i> {{ config('company.address') }}</p>
                </div>
                <div class="social-links mt-4">
                    <a href="{{ config('company.social.facebook') }}" class="btn btn-outline-primary"><i class="fab fa-facebook"></i></a>
                    <a href="{{ config('company.social.twitter') }}" class="btn btn-outline-primary"><i class="fab fa-twitter"></i></a>
                    <a href="{{ config('company.social.instagram') }}" class="btn btn-outline-primary"><i class="fab fa-instagram"></i></a>
                    <a href="{{ config('company.social.linkedin') }}" class="btn btn-outline-primary"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <form class="contact-form" action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="الاسم" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" class="form-control" placeholder="رقم الهاتف">
                    </div>
                    <div class="form-group">
                        <textarea name="message" class="form-control" rows="5" placeholder="رسالتك" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">إرسال</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-section {
    background-size: cover;
    background-position: center;
    padding: 150px 0;
    color: white;
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
}

.hero-title {
    font-size: 3.5rem;
    margin-bottom: 20px;
}

.service-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.service-card:hover {
    transform: translateY(-10px);
}

.project-card {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}

.project-info {
    position: absolute;
    bottom: -100%;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 20px;
    transition: bottom 0.3s;
}

.project-card:hover .project-info {
    bottom: 0;
}

.testimonial-item {
    background: white;
    padding: 30px;
    border-radius: 10px;
    margin: 15px;
}

.client-info {
    display: flex;
    align-items: center;
    margin-top: 20px;
}

.client-info img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-right: 15px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.clients-slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });

    $('.testimonials-slider').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
});
</script>
@endpush 