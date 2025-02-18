@extends('layouts.admin')

@section('content')
<div class="page-builder">
    <div class="builder-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>محرر الصفحات</h2>
                </div>
                <div class="col-md-6 text-left">
                    <div class="language-switcher d-inline-block ml-3">
                        @foreach(config('languages.available_locales') as $locale => $lang)
                        <button class="btn btn-{{ $currentLocale == $locale ? 'primary' : 'outline-primary' }}"
                                onclick="switchLanguage('{{ $locale }}')">
                            <span class="flag-icon flag-icon-{{ $lang['flag'] }}"></span>
                            {{ $lang['name'] }}
                        </button>
                        @endforeach
                    </div>
                    <button class="btn btn-success" onclick="savePage()">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="builder-content">
        <div class="container-fluid">
            <div class="row">
                <!-- قائمة العناصر -->
                <div class="col-md-3">
                    <div class="components-list card">
                        <div class="card-header">
                            <h5>العناصر</h5>
                        </div>
                        <div class="card-body">
                            <div class="component-item" draggable="true" data-type="hero">
                                <i class="fas fa-image"></i>
                                قسم رئيسي
                            </div>
                            <div class="component-item" draggable="true" data-type="text">
                                <i class="fas fa-paragraph"></i>
                                نص
                            </div>
                            <div class="component-item" draggable="true" data-type="image">
                                <i class="fas fa-image"></i>
                                صورة
                            </div>
                            <div class="component-item" draggable="true" data-type="gallery">
                                <i class="fas fa-images"></i>
                                معرض صور
                            </div>
                            <div class="component-item" draggable="true" data-type="video">
                                <i class="fas fa-video"></i>
                                فيديو
                            </div>
                            <div class="component-item" draggable="true" data-type="form">
                                <i class="fas fa-wpforms"></i>
                                نموذج
                            </div>
                            <div class="component-item" draggable="true" data-type="map">
                                <i class="fas fa-map-marked-alt"></i>
                                خريطة
                            </div>
                        </div>
                    </div>
                </div>

                <!-- منطقة التحرير -->
                <div class="col-md-6">
                    <div class="preview-area card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>محتوى الصفحة</h5>
                                <div class="preview-controls">
                                    <button class="btn btn-sm btn-outline-primary" onclick="previewMobile()">
                                        <i class="fas fa-mobile-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="previewTablet()">
                                        <i class="fas fa-tablet-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="previewDesktop()">
                                        <i class="fas fa-desktop"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="page-content" class="dropzone">
                                <!-- هنا يتم إضافة العناصر -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- خصائص العنصر -->
                <div class="col-md-3">
                    <div class="properties-panel card">
                        <div class="card-header">
                            <h5>الخصائص</h5>
                        </div>
                        <div class="card-body">
                            <div id="element-properties">
                                <!-- تظهر هنا خصائص العنصر المحدد -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-builder {
    min-height: calc(100vh - 60px);
}

.component-item {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    cursor: move;
    background: white;
}

.component-item:hover {
    background: #f8f9fa;
}

.dropzone {
    min-height: 500px;
    border: 2px dashed #ddd;
    padding: 20px;
}

.dropzone.dragover {
    background: #f8f9fa;
    border-color: #4e73df;
}

.element {
    position: relative;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    padding: 10px;
}

.element:hover {
    border-color: #4e73df;
}

.element-controls {
    position: absolute;
    top: 5px;
    right: 5px;
    display: none;
}

.element:hover .element-controls {
    display: block;
}

.preview-area {
    background: white;
    min-height: 600px;
}

.properties-panel {
    height: 100%;
}
</style>
@endpush

@push('scripts')
<script>
// تهيئة السحب والإفلات
document.addEventListener('DOMContentLoaded', function() {
    initDragAndDrop();
    loadPageContent();
});

function initDragAndDrop() {
    const components = document.querySelectorAll('.component-item');
    const dropzone = document.getElementById('page-content');

    components.forEach(component => {
        component.addEventListener('dragstart', handleDragStart);
    });

    dropzone.addEventListener('dragover', handleDragOver);
    dropzone.addEventListener('drop', handleDrop);
}

function handleDragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.dataset.type);
}

function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('dragover');
}

function handleDrop(e) {
    e.preventDefault();
    const type = e.dataTransfer.getData('text/plain');
    addElement(type);
    e.currentTarget.classList.remove('dragover');
}

function addElement(type) {
    const element = createElementByType(type);
    document.getElementById('page-content').appendChild(element);
    showProperties(element);
}

function createElementByType(type) {
    const element = document.createElement('div');
    element.className = 'element';
    element.dataset.type = type;
    
    // إضافة أزرار التحكم
    const controls = document.createElement('div');
    controls.className = 'element-controls';
    controls.innerHTML = `
        <button class="btn btn-sm btn-primary" onclick="editElement(this)">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-danger" onclick="deleteElement(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    // إضافة المحتوى حسب النوع
    const content = document.createElement('div');
    content.className = 'element-content';
    switch(type) {
        case 'hero':
            content.innerHTML = `
                <div class="hero-section">
                    <h1 contenteditable="true">عنوان رئيسي</h1>
                    <p contenteditable="true">نص توضيحي</p>
                </div>
            `;
            break;
        case 'text':
            content.innerHTML = `
                <div contenteditable="true">
                    <p>انقر للتعديل</p>
                </div>
            `;
            break;
        // ... المزيد من الأنواع
    }
    
    element.appendChild(controls);
    element.appendChild(content);
    return element;
}

function showProperties(element) {
    const panel = document.getElementById('element-properties');
    panel.innerHTML = getPropertiesForm(element.dataset.type);
}

function getPropertiesForm(type) {
    switch(type) {
        case 'hero':
            return `
                <div class="form-group">
                    <label>خلفية القسم</label>
                    <input type="file" class="form-control" onchange="updateBackground(this)">
                </div>
                <div class="form-group">
                    <label>ارتفاع القسم</label>
                    <select class="form-control" onchange="updateHeight(this)">
                        <option value="small">صغير</option>
                        <option value="medium">متوسط</option>
                        <option value="large">كبير</option>
                    </select>
                </div>
            `;
        // ... المزيد من الأنواع
    }
}

function savePage() {
    const content = document.getElementById('page-content').innerHTML;
    // حفظ المحتوى عبر AJAX
    axios.post('/admin/pages/save', {
        content: content,
        locale: currentLocale
    }).then(response => {
        showAlert('success', 'تم حفظ التغييرات بنجاح');
    }).catch(error => {
        showAlert('error', 'حدث خطأ أثناء الحفظ');
    });
}

function switchLanguage(locale) {
    currentLocale = locale;
    loadPageContent();
}

function loadPageContent() {
    axios.get(`/admin/pages/content/${currentLocale}`).then(response => {
        document.getElementById('page-content').innerHTML = response.data.content;
    });
}
</script>
@endpush 