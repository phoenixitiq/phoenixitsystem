@extends('exports.pdf.layout')

@section('content')
<div class="official-letter deduction">
    <div class="letter-header">
        <h1>قرار خصم</h1>
        <div class="letter-info">
            <p>رقم القرار: {{ $data['letter']->letter_number }}</p>
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
            الموضوع: قرار خصم من الراتب
        </p>

        <div class="content">
            {!! $data['letter']->content !!}
        </div>

        <div class="deduction-details">
            <h4>تفاصيل الخصم:</h4>
            <p>المبلغ: {{ $data['letter']->amount }} {{ $data['letter']->currency_code }}</p>
            <p>سبب الخصم: {{ $data['letter']->reason }}</p>
            @if($data['letter']->installments)
            <p>عدد الأقساط: {{ $data['letter']->installments }}</p>
            <p>قيمة القسط: {{ $data['letter']->installment_amount }}</p>
            @endif
            <p>تاريخ تطبيق الخصم: {{ $data['letter']->effective_date }}</p>
        </div>

        @if($data['letter']->notes)
        <div class="notes">
            <h4>ملاحظات:</h4>
            <p>{{ $data['letter']->notes }}</p>
        </div>
        @endif

        <div class="legal-reference">
            <p>استناداً إلى:</p>
            <ul>
                <li>المادة ({{ $data['letter']->law_article }}) من قانون العمل</li>
                <li>اللائحة الداخلية للشركة</li>
            </ul>
        </div>
    </div>

    <div class="signatures">
        <div class="hr-manager">
            <p>مدير الموارد البشرية</p>
            <div class="signature-line"></div>
            <p>{{ $data['letter']->created_by_name }}</p>
        </div>
        <div class="finance-manager">
            <p>المدير المالي</p>
            <div class="signature-line"></div>
            <p>{{ $data['letter']->approved_by_name }}</p>
        </div>
        <div class="employee-signature">
            <p>توقيع الموظف بالعلم</p>
            <div class="signature-line"></div>
            <p>التاريخ: ________________</p>
        </div>
    </div>
</div>
@endsection 