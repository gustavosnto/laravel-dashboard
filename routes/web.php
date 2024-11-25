<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FinancialController; 
use App\Http\Controllers\FinancialClientController; 
use App\Http\Controllers\FInancialBillingController; 
use App\Http\Controllers\AsaasController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('dashboard/business', BusinessController::class);
    Route::resource('dashboard/projects', ProjectController::class); 
    Route::delete('dashboard/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Financeiro Routes
    Route::get('dashboard/financial', [FinancialController::class, 'index'])->name('financial.index');

    Route::prefix('dashboard/financial/client')->group(function () {
        Route::get('/', [FinancialClientController::class, 'index'])->name('financial.client.index');
        Route::get('/create', [FinancialClientController::class, 'create'])->name('financial.client.create');
        Route::post('/', [FinancialClientController::class, 'store'])->name('financial.client.store');
        Route::get('{id}', [FinancialClientController::class, 'show'])->name('financial.client.show');
        Route::get('{id}/edit', [FinancialClientController::class, 'edit'])->name('financial.client.edit');
        Route::put('{id}', [FinancialClientController::class, 'update'])->name('financial.client.update');
        Route::delete('{id}', [FinancialClientController::class, 'destroy'])->name('financial.client.destroy');
    });

    Route::prefix('dashboard/financial/billing')->group(function () {
        Route::get('/', [FInancialBillingController::class, 'index'])->name('financial.billing.index');
        Route::get('/create', [FInancialBillingController::class, 'create'])->name('financial.billing.create');
        Route::post('/', [FInancialBillingController::class, 'store'])->name('financial.billing.store');
        Route::get('{id}', [FInancialBillingController::class, 'show'])->name('financial.billing.show');
        Route::get('{id}/edit', [FInancialBillingController::class, 'edit'])->name('financial.billing.edit');
        Route::put('{id}', [FInancialBillingController::class, 'update'])->name('financial.billing.update');
        Route::delete('{id}', [FInancialBillingController::class, 'destroy'])->name('financial.billing.destroy');
    });
    // Route::get('dashboard/financial', [FinancialController::class, 'index'])->name('financial.index');
    // Route::get('dashboard/financial/create', [FinancialController::class, 'create'])->name('financial.create');
    // Route::post('dashboard/financial', [FinancialController::class, 'store'])->name('financial.store');
    // Route::get('dashboard/financial/{id}', [FinancialController::class, 'show'])->name('financial.show');
    // Route::get('dashboard/financial/{id}/edit', [FinancialController::class, 'edit'])->name('financial.edit');

    Route::get('api/financial/customers', [AsaasController::class, 'index']);
    Route::get('/api/financial/customers/{id}', [AsaasController::class, 'show']);
    Route::post('api/financial/customers', [AsaasController::class, 'store']);
    Route::put('api/financial/customers/{id}', [AsaasController::class, 'update']);
    Route::delete('api/financial/customers/{id}', [AsaasController::class, 'destroy']);
});

require __DIR__.'/auth.php';
