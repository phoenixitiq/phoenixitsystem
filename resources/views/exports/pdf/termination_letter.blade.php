@extends('exports.pdf.layout')

@section('content')
<div class="official-letter termination">
    <div class="letter-header">
        <h1>إنهاء خدمات</h1>
        <div class="letter-info">
            <p>رقم القرار: {{ $data['letter']->letter_number }}</p>
            <p>التاريخ: {{ $data['date'] }}</p>
        </div>
    </div>

    <div class="employee-info">
        <p>السيد/ة: {{ $data['letter']->employee->name }}</p>
        <p>المنصب: {{ $data['letter']->employee->position }}</p>
        <p>تاريخ التعيين: {{ $data['letter']->employee->hire_date }}</p>
    </div>

    <div class="letter-body">
        <p class="subject">
            الموضوع: إنهاء خدمات
        </p>

        <div class="content">
            {!! $data['letter']->content !!}
        </div>

        <div class="termination-details">
            <p>تاريخ آخر يوم عمل: {{ $data['letter']->last_working_day }}</p>
            <p>فترة الإشعار: {{ $data['letter']->notice_period }} يوم</p>
            @if($data['letter']->compensation)
            <p>التعويضات المستحقة: {{ $data['letter']->compensation }}</p>
            @endif
        </div>

        <div class="final-settlement">
            <h4>التسوية النهائية تشمل:</h4>
            <ul>
                @foreach($data['letter']->settlement_items as $item)
                <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="signatures">
        <div class="hr-manager">
            <p>مدير الموارد البشرية</p>
            <div class="signature-line"></div>
            <p>{{ $data['letter']->created_by_name }}</p>
        </div>
        <div class="ceo">
            <p>المدير التنفيذي</p>
            <div class="signature-line"></div>
            <p>{{ $data['letter']->approved_by_name }}</p>
        </div>
        <div class="employee-signature">
            <p>استلمت نسخة من هذا القرار</p>
            <div class="signature-line"></div>
            <p>التاريخ: ________________</p>
        </div>
    </div>
</div>
@endsection 