@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>إدارة العملات</h2>

    <div class="card">
        <div class="card-header">
            <h3>العملات المدعومة</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>الرمز</th>
                        <th>الاسم</th>
                        <th>الرمز</th>
                        <th>سعر الصرف</th>
                        <th>الحالة</th>
                        <th>العملة الافتراضية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currencies as $currency)
                    <tr>
                        <td>{{ $currency->code }}</td>
                        <td>{{ $currency->name }}</td>
                        <td>{{ $currency->symbol }}</td>
                        <td>{{ $currency->exchange_rate }}</td>
                        <td>
                            <span class="badge badge-{{ $currency->is_active ? 'success' : 'danger' }}">
                                {{ $currency->is_active ? 'مفعل' : 'معطل' }}
                            </span>
                        </td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input set-default"
                                    id="default_{{ $currency->code }}"
                                    {{ $currency->is_default ? 'checked' : '' }}
                                    data-currency="{{ $currency->code }}">
                                <label class="custom-control-label" for="default_{{ $currency->code }}"></label>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-rate"
                                data-currency="{{ $currency->code }}"
                                data-rate="{{ $currency->exchange_rate }}">
                                تعديل السعر
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal تعديل سعر الصرف -->
<div class="modal fade" id="editRateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل سعر الصرف</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRateForm">
                    <input type="hidden" name="currency_code" id="currency_code">
                    <div class="form-group">
                        <label>سعر الصرف</label>
                        <input type="number" step="0.0001" class="form-control" 
                            name="exchange_rate" id="exchange_rate" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="saveRate">حفظ</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // تعيين العملة الافتراضية
    $('.set-default').change(function() {
        const currencyCode = $(this).data('currency');
        $.post('/api/currency/set-default', {
            currency_code: currencyCode,
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                $('.set-default').not(`#default_${currencyCode}`).prop('checked', false);
            } else {
                toastr.error(response.message);
            }
        });
    });

    // تعديل سعر الصرف
    $('.edit-rate').click(function() {
        const currency = $(this).data('currency');
        const rate = $(this).data('rate');
        $('#currency_code').val(currency);
        $('#exchange_rate').val(rate);
        $('#editRateModal').modal('show');
    });

    $('#saveRate').click(function() {
        const data = $('#editRateForm').serialize();
        $.post('/api/currency/update-rate', data)
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            });
    });
});
</script>
@endpush 