<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:super_admin|admin|moderateur', 'require.2fa'])
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
    });
