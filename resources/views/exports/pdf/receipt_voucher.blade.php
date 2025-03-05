@extends('exports.pdf.layout')

@section('content')
<div class="voucher receipt">
    <div class="voucher-header">
        <h1>سند قبض</h1>
        <div class="voucher-info">
            <p>رقم السند: {{ $data['voucher']->voucher_number }}</p>
            <p>التاريخ: {{ $data['date'] }}</p>
        </div>
    </div>

    <div class="voucher-body">
        <p class="received-from">
            استلمنا من السيد/ة: <span>{{ $data['voucher']->received_from }}</span>
        </p>
        
        <p class="amount">
            مبلغ وقدره: <span>{{ $data['voucher']->amount }}</span>
            <span class="currency">{{ $data['voucher']->currency_code }}</span>
        </p>
        
        <p class="amount-text">
            فقط {{ $data['voucher']->amount_in_words }} لا غير
        </p>

        <p class="payment-method">
            طريقة الدفع: {{ $data['voucher']->payment_method }}
            @if($data['voucher']->payment_details)
                <br>
                التفاصيل: {{ json_encode($data['voucher']->payment_details, JSON_UNESCAPED_UNICODE) }}
            @endif
        </p>

        <p class="description">
            وذلك عن: {{ $data['voucher']->description }}
        </p>
    </div>

    <div class="signatures">
        <div class="receiver">
            <p>المستلم</p>
            <div class="signature-line"></div>
            <p>{{ $data['voucher']->created_by_name }}</p>
        </div>
        <div class="approver">
            <p>المدير المالي</p>
            <div class="signature-line"></div>
            <p>{{ $data['voucher']->approved_by_name ?? '________________' }}</p>
        </div>
    </div>
</div>
@endsection 