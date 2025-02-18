<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Services\InstallService;
use App\Services\RequirementsChecker;
use Illuminate\Http\Request;

class InstallController extends Controller
{
    protected $installService;
    protected $requirementsChecker;

    public function __construct(InstallService $installService, RequirementsChecker $requirementsChecker)
    {
        $this->installService = $installService;
        $this->requirementsChecker = $requirementsChecker;
    }

    public function welcome()
    {
        return view('install.welcome', [
            'pageTitle' => 'مرحباً بك في نظام Phoenix IT'
        ]);
    }

    public function requirements()
    {
        $requirements = $this->requirementsChecker->check();
        return view('install.requirements', [
            'pageTitle' => 'متطلبات النظام',
            'requirements' => $requirements,
            'canProceed' => $this->requirementsChecker->canProceed()
        ]);
    }

    public function database()
    {
        return view('install.database', [
            'pageTitle' => 'إعداد قاعدة البيانات'
        ]);
    }

    public function system()
    {
        return view('install.system', [
            'pageTitle' => 'إعدادات النظام'
        ]);
    }

    public function finish()
    {
        return view('install.finish', [
            'pageTitle' => 'اكتمل التثبيت',
            'admin_email' => session('admin_email')
        ]);
    }
} 