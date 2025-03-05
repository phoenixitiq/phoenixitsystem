<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function create(Package $package)
    {
        return view('subscriptions.create', [
            'package' => $package,
            'durations' => $package->getAvailableDurations(),
            'billingCycles' => $package->getBillingCycles()
        ]);
    }

    public function store(Request $request, Package $package)
    {
        $request->validate([
            'duration' => 'required|integer|min:' . $package->min_duration . '|max:' . $package->max_duration,
            'billing_cycle' => 'required|in:monthly,full_contract',
            'start_date' => 'required|date|after_or_equal:today'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = $startDate->copy()->addMonths($request->duration);
        
        $prices = $package->calculatePrice($request->duration, $request->billing_cycle);

        $subscription = Subscription::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $request->duration,
            'billing_cycle' => $request->billing_cycle,
            'total_amount' => $prices['total_amount'],
            'monthly_amount' => $prices['monthly_amount']
        ]);

        // توجيه لصفحة الدفع
        return redirect()->route('payments.create', $subscription);
    }
} 