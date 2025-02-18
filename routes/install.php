<?php

use App\Http\Controllers\Install\InstallController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'install', 'middleware' => 'install'], function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('install.welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('install.requirements');
    Route::get('/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/database', [InstallController::class, 'setupDatabase'])->name('install.database.setup');
    Route::get('/system', [InstallController::class, 'system'])->name('install.system');
    Route::post('/system', [InstallController::class, 'setupSystem'])->name('install.system.setup');
    Route::get('/finish', [InstallController::class, 'finish'])->name('install.finish');
}); 