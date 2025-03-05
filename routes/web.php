<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminAgentController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Admin\AdminBackupController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminLeaveController;
use App\Http\Controllers\RemoteWorkController;
use App\Http\Controllers\Admin\ContentController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Install\InstallController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\CookieController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function() {
    if(!file_exists(storage_path('app/installed'))) {
        return redirect('/install');
    }
    try {
        \DB::connection()->getPdo();
        return auth()->check() ? redirect('/dashboard') : redirect('/login');
    } catch(\Exception $e) {
        \Log::error('DB:'.$e->getMessage());
        return redirect('/install')->with('error', 'فشل الاتصال بقاعدة البيانات');
    }
});

// مسارات التثبيت
Route::prefix('install')->middleware(['web'])->group(function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('install.welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('install.requirements');
    Route::get('/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/database', [InstallController::class, 'setupDatabase'])->name('install.database.setup');
    Route::get('/complete', [InstallController::class, 'complete'])->name('install.complete');
});

// الصفحات العامة
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/become-agent', [AgentController::class, 'register'])->name('agent.register');

// مسارات المستخدمين
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
});

// لوحة التحكم
Route::prefix('admin')->middleware(['auth', 'role:admin,super-admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/statistics', [AdminDashboardController::class, 'statistics'])->name('admin.statistics');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');

    // إدارة المستخدمين والصلاحيات
    Route::resource('users', AdminUserController::class);
    Route::resource('roles', AdminRoleController::class);
    Route::resource('permissions', AdminPermissionController::class);

    // إدارة المحتوى
    Route::resource('services', AdminServiceController::class);
    Route::resource('projects', AdminProjectController::class);
    Route::resource('packages', AdminPackageController::class);
    Route::resource('blog', AdminBlogController::class);

    // إدارة الفريق والوكلاء
    Route::resource('team', AdminTeamController::class);
    Route::resource('agents', AdminAgentController::class);

    // إدارة الطلبات والمدفوعات
    Route::resource('orders', AdminOrderController::class);
    Route::resource('payments', AdminPaymentController::class);
    Route::resource('subscriptions', AdminSubscriptionController::class);

    // إعدادات النظام
    Route::get('settings', [AdminSettingController::class, 'index'])->name('admin.settings');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
    
    // النسخ الاحتياطي
    Route::get('backup', [AdminBackupController::class, 'index'])->name('admin.backup');
    Route::post('backup/create', [AdminBackupController::class, 'create'])->name('admin.backup.create');
    Route::post('backup/restore', [AdminBackupController::class, 'restore'])->name('admin.backup.restore');

    // استيراد بيانات دفترة
    Route::post('/import/daftra', [AdminBackupController::class, 'importDaftraBackup'])->name('admin.import.daftra');
});

// مسارات الفريق والتوظيف
Route::prefix('team')->group(function() {
    Route::get('/', [TeamController::class, 'index'])->name('team.index');
});

// مسارات التوظيف منفصلة
Route::prefix('careers')->group(function() {
    Route::get('/', [CareerController::class, 'index'])->name('careers.index');
    Route::get('/{position}', [CareerController::class, 'show'])->name('careers.show');
    Route::post('/apply', [CareerController::class, 'apply'])->name('careers.apply');
});

// الوكلاء
Route::prefix('agents')->group(function () {
    Route::get('/register', [AgentController::class, 'register'])->name('agents.register');
    Route::post('/register', [AgentController::class, 'store'])->name('agents.store');
    Route::get('/territories', [AgentController::class, 'territories'])->name('agents.territories');
});

// الباقات والاشتراكات
Route::prefix('packages')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('packages.index');
    Route::get('/{package}', [PackageController::class, 'show'])->name('packages.show');
    Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscriptions.store');
});

// المصادقة
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

// التقييمات
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// مسارات المحادثة
Route::prefix('chat')->middleware('auth')->group(function () {
    Route::get('/rooms', [ChatController::class, 'index'])->name('chat.rooms');
    Route::post('/rooms', [ChatController::class, 'store'])->name('chat.rooms.store');
    Route::get('/rooms/{room}', [ChatController::class, 'show'])->name('chat.rooms.show');
    Route::post('/rooms/{room}/messages', [ChatController::class, 'sendMessage'])->name('chat.messages.send');
});

// مسارات تحديد الموقع والتفضيلات
Route::prefix('location')->middleware('auth')->group(function () {
    Route::get('/detect', [LocationController::class, 'detect'])->name('location.detect');
    Route::post('/preferences', [LocationController::class, 'updatePreferences'])->name('location.preferences');
});

// مسارات النسخ الاحتياطي
Route::prefix('admin/system')->middleware(['auth', 'admin'])->group(function () {
    Route::post('/sync', [SystemController::class, 'sync'])->name('system.sync');
    Route::post('/backup', [SystemController::class, 'backup'])->name('system.backup');
    Route::get('/backup/download/{filename}', [SystemController::class, 'downloadBackup'])->name('system.backup.download');
});

// مسارات السوبر أدمن
Route::prefix('super-admin')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard']);
    Route::get('/system-logs', [SuperAdminController::class, 'systemLogs']);
    Route::get('/server-status', [SuperAdminController::class, 'serverStatus']);
    Route::get('/all-branches', [SuperAdminController::class, 'branches']);
    Route::get('/admin-management', [SuperAdminController::class, 'adminManagement']);
    Route::get('/global-settings', [SuperAdminController::class, 'globalSettings']);
});

// مسارات الموظفين
Route::prefix('employee')->middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard']);
    Route::get('/tasks', [EmployeeController::class, 'tasks']);
    Route::get('/attendance', [EmployeeController::class, 'attendance']);
    Route::get('/projects', [EmployeeController::class, 'projects']);
    Route::get('/leaves', [EmployeeController::class, 'leaves']);
    Route::get('/performance', [EmployeeController::class, 'performance']);
});

// مسارات الوكلاء
Route::prefix('agent')->middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/dashboard', [AgentController::class, 'dashboard']);
    Route::get('/leads', [AgentController::class, 'leads']);
    Route::get('/commissions', [AgentController::class, 'commissions']);
    Route::get('/territories', [AgentController::class, 'territories']);
    Route::get('/marketing-materials', [AgentController::class, 'marketingMaterials']);
});

// مسارات الحضور والانصراف
Route::prefix('employee/attendance')->middleware(['auth', 'role:employee'])->group(function () {
    Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
});

// مسارات إدارة الحضور والانصراف للإدارة
Route::prefix('admin/attendance')->middleware(['auth', 'role:admin,super-admin'])->group(function () {
    Route::get('/', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::post('/update', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');
    Route::get('/report', [AdminAttendanceController::class, 'report'])->name('admin.attendance.report');
    Route::get('/leaves', [AdminLeaveController::class, 'index'])->name('admin.leaves.index');
    Route::post('/leaves/{leave}/approve', [AdminLeaveController::class, 'approve'])->name('admin.leaves.approve');
    Route::post('/leaves/{leave}/reject', [AdminLeaveController::class, 'reject'])->name('admin.leaves.reject');
    Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('admin.permissions.index');
    Route::post('/permissions/{permission}/approve', [AdminPermissionController::class, 'approve'])->name('admin.permissions.approve');
    Route::post('/permissions/{permission}/reject', [AdminPermissionController::class, 'reject'])->name('admin.permissions.reject');
});

// مسارات العمل عن بعد
Route::prefix('remote')->middleware(['auth', 'employee.remote'])->group(function () {
    Route::get('/attendance', [RemoteWorkController::class, 'index'])->name('remote.attendance');
    Route::post('/attendance/start', [RemoteWorkController::class, 'startWork'])->name('remote.attendance.start');
    Route::post('/attendance/end', [RemoteWorkController::class, 'endWork'])->name('remote.attendance.end');
    Route::get('/attendance/duration', [RemoteWorkController::class, 'getDuration'])->name('remote.attendance.duration');
    Route::post('/tasks', [RemoteWorkController::class, 'addTask'])->name('remote.tasks.add');
    Route::post('/tasks/update', [RemoteWorkController::class, 'updateTask'])->name('remote.tasks.update');
});

// مسارات إدارة المحتوى
Route::prefix('admin/content')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/pages/{page}/builder', [ContentController::class, 'pageBuilder'])->name('admin.pages.builder');
    Route::post('/pages/{page}/save', [ContentController::class, 'savePage'])->name('admin.pages.save');
    Route::get('/pages/{page}/content/{locale}', [ContentController::class, 'getPageContent'])->name('admin.pages.content');
});

// مسارات سياسة الكوكيز
Route::get('/cookie-policy', [CookieController::class, 'showConsentForm'])->name('cookie.consent');
Route::post('/cookie-accept', [CookieController::class, 'accept'])->name('cookie.accept');
Route::post('/cookie-reject', [CookieController::class, 'reject'])->name('cookie.reject');
