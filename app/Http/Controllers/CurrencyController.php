<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3'
        ]);

        $converted = $this->currencyService->convert(
            $request->amount,
            $request->from,
            $request->to
        );

        return response()->json([
            'original_amount' => $request->amount,
            'converted_amount' => $converted,
            'from' => $request->from,
            'to' => $request->to
        ]);
    }

    public function setDefault(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|size:3'
        ]);

        $success = $this->currencyService->setDefaultCurrency($request->currency_code);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'تم تحديث العملة الافتراضية' : 'فشل تحديث العملة الافتراضية'
        ]);
    }
} 