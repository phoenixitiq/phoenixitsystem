@extends('layouts.email')

@section('content')
<div class="email-content">
    <h2>{{ $subject }}</h2>
    <p>عزيزي/عزيزتي {{ $application->name }},</p>
    
    @if($application->status === 'accepted')
        <p>يسعدنا إبلاغك بأنه تم قبول طلبك للوظيفة: {{ $application->position->title }}</p>
        <p>سيتم التواصل معك قريباً لتحديد موعد المقابلة.</p>
    @elseif($application->status === 'rejected')
        <p>نشكرك على اهتمامك بالانضمام إلى فريقنا.</p>
        <p>نأسف لإبلاغك بأنه تم اختيار مرشحين آخرين للوظيفة في هذه المرحلة.</p>
    @else
        <p>تم استلام طلبك للوظيفة: {{ $application->position->title }}</p>
        <p>سنقوم بمراجعة طلبك والرد عليك في أقرب وقت ممكن.</p>
    @endif

    <p>مع أطيب التحيات,<br>فريق التوظيف</p>
</div>
@endsection 