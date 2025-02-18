<?php

namespace App\Http\Controllers;

use App\Services\InstallService;
use App\Services\RequirementsChecker;
use Illuminate\Http\Request;
use Exception;

class InstallController extends Controller
{
    private $installService;
    private $requirementsChecker;

    public function __construct(InstallService $installService, RequirementsChecker $requirementsChecker)
    {
        $this->installService = $installService;
        $this->requirementsChecker = $requirementsChecker;
    }

    public function index()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('install.welcome');
    }

    public function requirements()
    {
        $requirements = $this->requirementsChecker->check();
        $canProceed = $this->canProceed($requirements);

        return view('install.requirements', compact('requirements', 'canProceed'));
    }

    public function database()
    {
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

            return redirect()->route('install.complete')
                ->with('success', 'تم تثبيت النظام بنجاح');
        } catch (Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    private function isInstalled()
    {
        return file_exists(storage_path('installed'));
    }

    private function canProceed($requirements)
    {
        if (!$requirements['php']['status']) {
            return false;
        }

        foreach ($requirements['extensions'] as $status) {
            if (!$status) return false;
        }

        foreach ($requirements['directories'] as $directory) {
            if (!$directory['writable']) return false;
        }

        return true;
    }
} 