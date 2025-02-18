@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>إدارة الحضور والانصراف</h3>
        </div>
        <div class="card-body">
            <!-- فلترة البيانات -->
            <form class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="employee" class="form-control">
                            <option value="">كل الموظفين</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">فلترة</button>
                    </div>
                </div>
            </form>

            <!-- إضافة أزرار التصدير -->
            <div class="export-buttons mb-3">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        تصدير التقرير
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.attendance.export', ['type' => 'excel']) }}" class="dropdown-item">
                            <i class="fas fa-file-excel"></i> تصدير Excel
                        </a>
                        <a href="{{ route('admin.attendance.export', ['type' => 'pdf']) }}" class="dropdown-item">
                            <i class="fas fa-file-pdf"></i> تصدير PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- جدول الحضور -->
            <table class="table">
                <thead>
                    <tr>
                        <th>الموظف</th>
                        <th>التاريخ</th>
                        <th>وقت الحضور</th>
                        <th>وقت الانصراف</th>
                        <th>الحالة</th>
                        <th>ساعات العمل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceRecords as $record)
                    <tr>
                        <td>{{ $record->employee->name }}</td>
                        <td>{{ $record->check_in->format('Y-m-d') }}</td>
                        <td>{{ $record->check_in->format('H:i') }}</td>
                        <td>{{ $record->check_out ? $record->check_out->format('H:i') : '-' }}</td>
                        <td>{{ $record->status }}</td>
                        <td>{{ $record->work_hours ?? '-' }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-record" 
                                data-id="{{ $record->id }}"
                                data-toggle="modal" 
                                data-target="#editModal">
                                تعديل
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- نافذة التعديل -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.attendance.update') }}" method="POST">
                @csrf
                <input type="hidden" name="record_id" id="record_id">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل سجل الحضور</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>وقت الحضور</label>
                        <input type="datetime-local" name="check_in" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>وقت الانصراف</label>
                        <input type="datetime-local" name="check_out" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>الحالة</label>
                        <select name="status" class="form-control">
                            <option value="present">حاضر</option>
                            <option value="absent">غائب</option>
                            <option value="late">متأخر</option>
                            <option value="early_leave">خروج مبكر</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ملاحظات</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 