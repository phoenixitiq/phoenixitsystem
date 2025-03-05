<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function showConsentForm()
    {
        return view('cookies.consent');
    }

    public function accept(Request $request)
    {
        $minutes = config('cookies.consent.duration') * 24 * 60;
        Cookie::queue(
            config('cookies.consent.cookie_name'),
            'accepted',
            $minutes
        );

        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        }
        return back();
    }

    public function reject(Request $request)
    {
        $minutes = config('cookies.consent.duration') * 24 * 60;
        Cookie::queue(
            config('cookies.consent.cookie_name'),
            'rejected',
            $minutes
        );
        
        $this->setEssentialCookies();

        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        }
        return back();
    }

    private function setEssentialCookies()
    {
        Cookie::queue('essential_only', 'true', config('cookies.consent.duration') * 24 * 60);
    }
} 