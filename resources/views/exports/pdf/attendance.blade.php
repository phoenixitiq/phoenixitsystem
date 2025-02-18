@extends('exports.pdf.layout')

@section('content')
<h1>تقرير الحضور والانصراف</h1>
<p>الفترة: {{ $data['period'] }}</p>

<table>
    <thead>
        <tr>
            <th>الموظف</th>
            <th>التاريخ</th>
            <th>وقت الحضور</th>
            <th>وقت الانصراف</th>
            <th>ساعات العمل</th>
            <th>الحالة</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['records'] as $record)
        <tr>
            <td>{{ $record->employee->name }}</td>
            <td>{{ $record->date }}</td>
            <td>{{ $record->check_in }}</td>
            <td>{{ $record->check_out }}</td>
            <td>{{ $record->work_hours }}</td>
            <td>{{ $record->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="summary">
    <h3>ملخص التقرير</h3>
    <p>إجمالي أيام العمل: {{ $data['summary']['total_days'] }}</p>
    <p>إجمالي ساعات العمل: {{ $data['summary']['total_hours'] }}</p>
    <p>متوسط ساعات العمل اليومية: {{ $data['summary']['average_hours'] }}</p>
</div>
@endsection 