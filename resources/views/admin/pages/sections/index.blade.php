@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">إدارة أقسام صفحة {{ __("pages.{$page->identifier}") }}</h1>
            <p class="text-muted">يمكنك إضافة وتعديل وترتيب أقسام الصفحة</p>
        </div>
        <a href="{{ route('admin.pages.sections.create', $page->identifier) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة قسم جديد
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;"></th>
                            <th>القسم</th>
                            <th>العنوان</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="sortable">
                        @foreach($sections as $section)
                        <tr data-id="{{ $section->id }}">
                            <td>
                                <i class="fas fa-grip-vertical text-muted cursor-move"></i>
                            </td>
                            <td>{{ __("sections.{$section->identifier}") }}</td>
                            <td>{{ json_decode($section->title)->ar }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input toggle-status"
                                           data-id="{{ $section->id }}"
                                           {{ $section->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.pages.sections.edit', [$page->identifier, $section->id]) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-item" 
                                            data-id="{{ $section->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل خاصية السحب والإفلات لترتيب الأقسام
    new Sortable(document.querySelector('.sortable'), {
        handle: '.cursor-move',
        animation: 150,
        onEnd: function(evt) {
            const itemId = evt.item.dataset.id;
            const newIndex = evt.newIndex;
            
            // تحديث الترتيب في قاعدة البيانات
            axios.post(`/admin/pages/sections/reorder`, {
                id: itemId,
                order: newIndex
            });
        }
    });

    // تفعيل/تعطيل القسم
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const isActive = this.checked;

            axios.post(`/admin/pages/sections/${id}/toggle`, {
                is_active: isActive
            });
        });
    });
});
</script>
@endpush
@endsection 