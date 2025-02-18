<div class="form-group">
    <label for="amount">المبلغ</label>
    <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
</div>

<div class="form-group">
    <label for="currency_code">العملة</label>
    <select name="currency_code" id="currency_code" class="form-control">
        <option value="IQD">دينار عراقي</option>
        <option value="USD">دولار أمريكي</option>
        <option value="EUR">يورو</option>
    </select>
</div>

<div class="converted-amount" style="display: none;">
    <p>المبلغ بالدينار العراقي: <span id="converted-amount"></span></p>
</div>

@push('scripts')
<script>
document.getElementById('currency_code').addEventListener('change', function() {
    const amount = document.getElementById('amount').value;
    if (this.value !== 'IQD' && amount) {
        fetch(`/api/convert-currency?amount=${amount}&from=${this.value}&to=IQD`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('converted-amount').textContent = 
                    new Intl.NumberFormat('ar-IQ', { style: 'currency', currency: 'IQD' })
                        .format(data.converted_amount);
                document.querySelector('.converted-amount').style.display = 'block';
            });
    } else {
        document.querySelector('.converted-amount').style.display = 'none';
    }
});
</script>
@endpush 