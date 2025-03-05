<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// منع الوصول للـ API أثناء التثبيت
if (!file_exists(storage_path('app/installed'))) {
    Route::any('{any}', function () {
        return response()->json([
            'error' => 'النظام قيد التثبيت',
            'message' => 'يرجى إكمال عملية التثبيت',
            'install_url' => '/install'
        ], 503);
    })->where('any', '.*');
    return;
}

// باقي مسارات API
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
