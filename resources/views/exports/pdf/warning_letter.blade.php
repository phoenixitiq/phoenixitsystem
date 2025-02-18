@extends('exports.pdf.layout')

@section('content')
<div class="official-letter warning">
    <div class="letter-header">
        <h1>إنذار رسمي</h1>
        <div class="letter-info">
            <p>رقم الإنذار: {{ $data['letter']->letter_number }}</p>
            <p>التاريخ: {{ $data['date'] }}</p>
        </div>
    </div>

    <div class="employee-info">
        <p>السيد/ة: {{ $data['letter']->employee->name }}</p>
        <p>المنصب: {{ $data['letter']->employee->position }}</p>
        <p>القسم: {{ $data['letter']->employee->department }}</p>
    </div>

    <div class="letter-body">
        <p class="subject">
            الموضوع: {{ $data['letter']->subject }}
        </p>

        <div class="content">
            {!! $data['letter']->content !!}
        </div>

        @if($data['letter']->action_required)
        <div class="required-action">
            <p>الإجراء المطلوب:</p>
            <p>{{ $data['letter']->action_required }}</p>
            @if($data['letter']->action_deadline)
            <p>الموعد النهائي: {{ $data['letter']->action_deadline }}</p>
            @endif
        </div>
        @endif

        <p class="warning-note">
            يرجى العلم أن تكرار هذه المخالفة سيؤدي إلى اتخاذ إجراءات أشد وفقاً لقانون العمل والأنظمة الداخلية للشركة.
        </p>
    </div>

    <div class="signatures">
        <div class="hr-manager">
            <p>مدير الموارد البشرية</p>
            <div class="signature-line"></div>
            <p>{{ $data['letter']->created_by_name }}</p>
        </div>
        <div class="employee-signature">
            <p>توقيع الموظف بالعلم</p>
            <div class="signature-line"></div>
            <p>التاريخ: ________________</p>
        </div>
    </div>
</div>
@endsection 