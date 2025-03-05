<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Services\InstallService;
use App\Services\RequirementsChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InstallController extends Controller
{
    protected $installService;
    protected $requirementsChecker;

    public function __construct(InstallService $installService, RequirementsChecker $requirementsChecker)
    {
        $this->installService = $installService;
        $this->requirementsChecker = $requirementsChecker;

        if (file_exists(base_path('.env'))) {
            return redirect('/');
        }
    }

    public function index()
    {
        return view('install.index');
    }

    public function welcome()
    {
        return view('install.steps.1_welcome', [
            'pageTitle' => 'مرحباً بك في نظام Phoenix IT'
        ]);
    }

    public function requirements()
    {
        $requirements = [
            'php' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'pdo' => extension_loaded('pdo'),
            'mbstring' => extension_loaded('mbstring'),
            'openssl' => extension_loaded('openssl'),
            'curl' => extension_loaded('curl'),
            'xml' => extension_loaded('xml'),
            'gd' => extension_loaded('gd'),
            'zip' => extension_loaded('zip')
        ];

        return view('install.steps.2_requirements', [
            'pageTitle' => 'متطلبات النظام',
            'requirements' => $requirements
        ]);
    }

    public function database()
    {
        return view('install.steps.3_database');
    }

    public function setupDatabase(Request $request)
    {
        if (file_exists(storage_path('app/installed'))) {
            return redirect('/');
        }

        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required|numeric|min:0|max:65535',
            'db_database' => 'required|regex:/^[a-zA-Z0-9_]+$/',
            'db_username' => 'required|regex:/^[a-zA-Z0-9_]+$/',
            'db_password' => 'nullable|min:4'
        ]);

        try {
            // حفظ إعدادات قاعدة البيانات
            $this->installService->saveEnvironmentConfig($request->all());

            // تنفيذ الهجرة
            $this->migrate();

            return redirect()->route('install.system');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'فشل الاتصال بقاعدة البيانات: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function system()
    {
        return view('install.steps.4_settings');
    }

    public function saveSystem(Request $request)
    {
        $request->validate([
            'app_name' => 'required',
            'app_url' => 'required|url',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:8'
        ]);

        try {
            // حفظ إعدادات النظام
            $this->installService->saveSystemSettings($request->all());

            // إنشاء حساب المدير
            $this->createAdmin($request);

            return redirect()->route('install.complete');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حفظ الإعدادات: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function createAdmin(Request $request)
    {
        DB::table('users')->insert([
            'name' => 'المدير',
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    protected function migrate()
    {
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
            return true;
        } catch (\Exception $e) {
            throw new \Exception('فشل تهيئة قاعدة البيانات: ' . $e->getMessage());
        }
    }

    public function complete()
    {
        return view('install.steps.5_complete');
    }

    public function finish()
    {
        try {
            // إنشاء ملف installed
            file_put_contents(storage_path('installed'), '');
            
            // حذف مجلد التثبيت
            File::deleteDirectory(resource_path('views/install'));
            
            // إعادة التوجيه للوحة التحكم
            return redirect('/login')->with('success', 'تم تثبيت النظام بنجاح');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إكمال التثبيت');
        }
    }
} 