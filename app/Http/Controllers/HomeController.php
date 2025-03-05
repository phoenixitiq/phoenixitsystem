<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'services' => $this->getMainServices()
        ]);
    }

    private function getMainServices()
    {
        return [
            'web_development' => [
                'title' => 'تطوير الويب',
                'description' => 'خدمات تطوير مواقع احترافية'
            ],
            'app_development' => [
                'title' => 'تطوير التطبيقات',
                'description' => 'تطبيقات iOS وAndroid'
            ],
            // ... باقي الخدمات
        ];
    }
} 