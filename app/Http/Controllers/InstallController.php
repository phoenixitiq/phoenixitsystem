namespace App\Http\Controllers;

use App\Services\InstallService;
use App\Services\RequirementsChecker;
use Illuminate\Http\Request;
use Exception;
use Log;

class InstallController extends Controller
{
    private $installService;
    private $requirementsChecker;

    public function __construct(InstallService $installService, RequirementsChecker $requirementsChecker)
    {
        $this->installService = $installService;
        $this->requirementsChecker = $requirementsChecker;
    }

    public function welcome()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }
        
        return view('install.welcome');
    }

    public function requirements()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        $requirements = $this->requirementsChecker->check();
        $canProceed = $this->canProceed($requirements);
        
        return view('install.requirements', compact('requirements', 'canProceed'));
    }

    public function database()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        return view('install.database');
    }

    public function setupDatabase(Request $request)
    {
        try {
            $validated = $request->validate([
                'db_connection' => 'required|in:mysql',
                'db_host' => 'required',
                'db_port' => 'required|numeric',
                'db_database' => 'required',
                'db_username' => 'required',
                'db_password' => 'nullable'
            ]);
            $this->installService->setupDatabase($validated);
            return redirect()->route('install.complete')->with('success', 'تم التثبيت بنجاح');
        } catch (Exception $e) {
            Log::error('Database Setup Error: ' . $e->getMessage());
            return back()->withErrors(['message' => 'خطأ أثناء إعداد قاعدة البيانات: ' . $e->getMessage()]);
        }
    }

    public function complete()
    {
        try {
            $paths = [
                storage_path(),
                storage_path('framework'),
                storage_path('framework/sessions'),
                storage_path('framework/views'),
                storage_path('framework/cache'),
                storage_path('logs')
            ];

            // التأكد من وجود المسارات وإنشائها إذا لزم الأمر
            foreach ($paths as $p) {
                if (!is_dir($p)) {
                    if (!mkdir($p, 0755, true) || !chmod($p, 0755)) {
                        throw new Exception("فشل في إنشاء الدليل: $p");
                    }
                }
            }

            // إنشاء ملف تثبيت في المسار المناسب
            if (!file_put_contents(storage_path('installed'), 'ok')) {
                throw new Exception('فشل في إنشاء ملف التثبيت');
            }

            // تنفيذ أوامر Artisan بعد التثبيت
            try {
                \Artisan::call('config:clear');
                \Artisan::call('cache:clear');
                \Artisan::call('view:clear');
                \Artisan::call('route:clear');
            } catch (Exception $e) {
                Log::warning('Artisan Command Error: ' . $e->getMessage());
            }

            return view('install.complete');
        } catch (Exception $e) {
            Log::error('Installation Error: ' . $e->getMessage());
            return back()->with('error', 'خطأ أثناء التثبيت: ' . $e->getMessage());
        }
    }

    private function isInstalled()
    {
        return file_exists(storage_path('installed'));
    }

    private function canProceed($requirements)
    {
        // تحقق من حالة PHP
        if (!$requirements['php']['status']) {
            return false;
        }

        // تحقق من جميع الإضافات
        foreach ($requirements['extensions'] as $extension => $status) {
            if (!isset($status) || !$status) {
                return false;
            }
        }

        // تحقق من جميع المجلدات وملاءمتها
        foreach ($requirements['directories'] as $directory) {
            if (!isset($directory['writable']) || !$directory['writable']) {
                return false;
            }
        }

        return true;
    }

    public function store(Request $request)
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }

        $validated = $request->validate([
            'db_host' => 'required',
            'db_port' => 'required|numeric',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'required'
        ]);

        try {
            $this->installService->setupDatabase($validated);
            
            // إنشاء ملف installed
            file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
            
            return redirect('/')->with('success', 'تم تثبيت النظام بنجاح');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
