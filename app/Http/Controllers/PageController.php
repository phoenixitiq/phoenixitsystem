<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Service;
use App\Models\Package;
use App\Models\Project;
use App\Models\ContentBlock;
use App\Models\Review;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\CompletedProject;

class PageController extends Controller
{
    public function home()
    {
        $data = [
            'company' => CompanySetting::getGroup('general'),
            'stats' => CompanySetting::getStats(),
            'services_stats' => CompanySetting::getServicesStats(),
            'services' => Service::featured()->take(6)->get(),
            'packages' => Package::where('is_active', true)->take(3)->get(),
            'projects' => Project::with('client')->latest()->take(4)->get(),
            'hero' => ContentBlock::getContent('home_hero'),
            'featured_reviews' => Review::where('is_featured', true)
                                     ->where('is_approved', true)
                                     ->take(3)
                                     ->get()
        ];

        return view('pages.home', $data);
    }

    public function about()
    {
        $data = [
            'company' => CompanySetting::getGroup('general'),
            'vision' => ContentBlock::getContent('about_vision'),
            'mission' => ContentBlock::getContent('about_mission'),
            'team' => Employee::where('is_team_member', true)
                            ->orderBy('display_order')
                            ->get()
        ];

        return view('pages.about', $data);
    }

    public function contact()
    {
        $data = [
            'contact' => CompanySetting::getGroup('contact'),
            'branches' => Branch::all(),
            'support' => CompanySetting::getSupportInfo(),
            'location' => CompanySetting::getLocation(),
            'legal' => CompanySetting::getLegalInfo(),
            'social' => CompanySetting::getGroup('social')
        ];

        return view('pages.contact', $data);
    }

    public function payment()
    {
        $data = [
            'payment_methods' => CompanySetting::getPaymentMethods(),
            'bank_info' => CompanySetting::getBankInfo(),
            'company' => CompanySetting::getGroup('general'),
            'legal' => CompanySetting::getLegalInfo()
        ];

        return view('pages.payment', $data);
    }

    public function services()
    {
        $data = [
            'services' => Service::with('features')
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(),
            'stats' => CompanySetting::getServicesStats(),
            'completed_projects' => CompletedProject::where('is_featured', true)
                ->take(6)
                ->get()
        ];

        return view('pages.services', $data);
    }

    public function portfolio()
    {
        $data = [
            'projects' => CompletedProject::orderBy('completion_date', 'desc')->paginate(12),
            'categories' => CompletedProject::select('category')
                            ->distinct()
                            ->pluck('category'),
            'stats' => [
                'total_projects' => CompletedProject::count(),
                'happy_clients' => CompanySetting::get('team_stats_clients'),
                'countries' => Branch::count()
            ]
        ];

        return view('pages.portfolio', $data);
    }

    public function serviceDetails($slug)
    {
        $service = Service::with(['features' => function($query) {
            $query->orderBy('display_order');
        }])->where('slug', $slug)->firstOrFail();

        $data = [
            'service' => $service,
            'related_services' => Service::where('category', $service->category)
                                ->where('id', '!=', $service->id)
                                ->take(3)
                                ->get(),
            'projects' => CompletedProject::where('category', $service->category)
                        ->take(4)
                        ->get()
        ];

        return view('pages.service-details', $data);
    }
} 