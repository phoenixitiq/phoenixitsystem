@extends('exports.pdf.layout')

@section('content')
<div class="contract">
    <h1>عقد عمل</h1>
    <div class="contract-header">
        <p>رقم العقد: {{ $data['contract']->contract_number }}</p>
        <p>التاريخ: {{ $data['date'] }}</p>
    </div>

    <div class="parties">
        <div class="first-party">
            <h3>الطرف الأول (الشركة)</h3>
            <p>{{ $data['company']['name'] }}</p>
            <p>{{ $data['company']['address'] }}</p>
        </div>

        <div class="second-party">
            <h3>الطرف الثاني (الموظف)</h3>
            <p>{{ $data['contract']->employee->name }}</p>
            <p>المنصب: {{ $data['contract']->position }}</p>
        </div>
    </div>

    <div class="terms">
        <h3>شروط العقد</h3>
        {!! $data['contract']->terms !!}
    </div>

    <div class="financial">
        <h3>التفاصيل المالية</h3>
        <p>الراتب الأساسي: {{ $data['contract']->salary }}</p>
        <div class="benefits">
            <h4>المزايا والبدلات:</h4>
            @foreach($data['contract']->benefits as $benefit => $value)
                <p>{{ $benefit }}: {{ $value }}</p>
            @endforeach
        </div>
    </div>

    <div class="signatures">
        <div class="company-signature">
            <p>توقيع الشركة</p>
            <div class="signature-line"></div>
        </div>
        <div class="employee-signature">
            <p>توقيع الموظف</p>
            <div class="signature-line"></div>
        </div>
    </div>
</div>
@endsection 