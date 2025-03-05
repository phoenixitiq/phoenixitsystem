@extends('exports.pdf.layout')

@section('content')
<div class="expense-report">
    <div class="report-header">
        <h1>تقرير المصروفات</h1>
        <div class="report-info">
            <p>الفترة: {{ $data['period'] }}</p>
            <p>تاريخ التقرير: {{ $data['date'] }}</p>
        </div>
    </div>

    <div class="report-body">
        <!-- ملخص المصروفات حسب الفئة -->
        <div class="category-summary">
            <h3>ملخص المصروفات حسب الفئة</h3>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>الفئة</th>
                        <th>المبلغ</th>
                        <th>النسبة</th>
                        <th>الميزانية</th>
                        <th>الفرق</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['categories'] as $category)
                    <tr>
                        <td>{{ $category->name_ar }}</td>
                        <td>{{ $category->total_amount }}</td>
                        <td>{{ $category->percentage }}%</td>
                        <td>{{ $category->budget }}</td>
                        <td class="{{ $category->difference < 0 ? 'text-danger' : 'text-success' }}">
                            {{ $category->difference }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>الإجمالي</th>
                        <th>{{ $data['total_amount'] }}</th>
                        <th>100%</th>
                        <th>{{ $data['total_budget'] }}</th>
                        <th>{{ $data['total_difference'] }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- تفاصيل المصروفات -->
        <div class="expense-details">
            <h3>تفاصيل المصروفات</h3>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>رقم المصروف</th>
                        <th>الفئة</th>
                        <th>الوصف</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['expenses'] as $expense)
                    <tr>
                        <td>{{ $expense->date }}</td>
                        <td>{{ $expense->expense_number }}</td>
                        <td>{{ $expense->category_name }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->amount }}</td>
                        <td>{{ $expense->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- الرسم البياني للمصروفات -->
        <div class="expense-chart">
            <h3>التوزيع النسبي للمصروفات</h3>
            <img src="{{ $data['chart_image'] }}" alt="رسم بياني للمصروفات">
        </div>
    </div>

    <div class="report-footer">
        <div class="prepared-by">
            <p>إعداد: {{ $data['prepared_by'] }}</p>
            <div class="signature-line"></div>
        </div>
        <div class="approved-by">
            <p>اعتماد: {{ $data['approved_by'] }}</p>
            <div class="signature-line"></div>
        </div>
    </div>
</div>
@endsection 