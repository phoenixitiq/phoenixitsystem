@extends('exports.pdf.layout')

@section('content')
<div class="voucher payment">
    <div class="voucher-header">
        <h1>سند صرف</h1>
        <div class="voucher-info">
            <p>رقم السند: {{ $data['voucher']->voucher_number }}</p>
            <p>التاريخ: {{ $data['date'] }}</p>
        </div>
    </div>

    <div class="voucher-body">
        <p class="paid-to">
            اصرفوا إلى السيد/ة: <span>{{ $data['voucher']->paid_to }}</span>
        </p>
        
        <p class="amount">
            مبلغ وقدره: <span>{{ $data['voucher']->amount }}</span>
            <span class="currency">{{ $data['voucher']->currency_code }}</span>
        </p>
        
        <p class="amount-text">
            فقط {{ $data['voucher']->amount_in_words }} لا غير
        </p>

        <p class="payment-method">
            طريقة الصرف: {{ $data['voucher']->payment_method }}
            @if($data['voucher']->payment_details)
                <br>
                التفاصيل: {{ json_encode($data['voucher']->payment_details, JSON_UNESCAPED_UNICODE) }}
            @endif
        </p>

        <p class="description">
            وذلك مقابل: {{ $data['voucher']->description }}
        </p>
    </div>

    <div class="signatures">
        <div class="payer">
            <p>أمين الصندوق</p>
            <div class="signature-line"></div>
            <p>{{ $data['voucher']->created_by_name }}</p>
        </div>
        <div class="approver">
            <p>المدير المالي</p>
            <div class="signature-line"></div>
            <p>{{ $data['voucher']->approved_by_name ?? '________________' }}</p>
        </div>
        <div class="receiver">
            <p>المستلم</p>
            <div class="signature-line"></div>
            <p>{{ $data['voucher']->paid_to }}</p>
        </div>
    </div>
</div>
@endsection 