<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ProjectService;
use App\Services\MarketingService;

class ServiceController extends Controller
{
    public function webDevelopment()
    {
        return view('services.web-development', [
            'services' => Service::where('category', 'web')->get(),
            'projects' => ProjectService::getWebProjects()
        ]);
    }

    public function digitalMarketing()
    {
        return view('services.digital-marketing', [
            'services' => Service::where('category', 'marketing')->get(),
            'campaigns' => MarketingService::getActiveCampaigns()
        ]);
    }
    
    // ... باقي الخدمات
} 