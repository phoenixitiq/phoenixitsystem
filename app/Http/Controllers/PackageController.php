<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('package_type', 'social_media')
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('packages.index', compact('packages'));
    }

    public function show(Package $package)
    {
        // التأكد من أن الباقة نشطة
        if (!$package->is_active) {
            abort(404);
        }

        return view('packages.show', compact('package'));
    }

    public function compare()
    {
        $packages = Package::where('package_type', 'social_media')
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('packages.compare', compact('packages'));
    }
} 