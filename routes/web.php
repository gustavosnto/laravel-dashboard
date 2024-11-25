<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\FinancialClientController;
use App\Http\Controllers\FinancialBillingController;
use App\Http\Controllers\FinancialSubscriptionController;
use App\Http\Controllers\AsaasController;
use App\Http\Controllers\AsaasBillingController;
use App\Http\Controllers\AsaasSubscriptionController;
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
        Route::get('/', [FinancialBillingController::class, 'index'])->name('financial.billing.index');
        Route::get('/create', [FinancialBillingController::class, 'create'])->name('financial.billing.create');
        Route::post('/', [FinancialBillingController::class, 'store'])->name('financial.billing.store');
        Route::get('{id}', [FinancialBillingController::class, 'show'])->name('financial.billing.show');
        Route::get('{id}/edit', [FinancialBillingController::class, 'edit'])->name('financial.billing.edit');
        Route::put('{id}', [FinancialBillingController::class, 'update'])->name('financial.billing.update');
        Route::delete('{id}', [FinancialBillingController::class, 'destroy'])->name('financial.billing.destroy');
    });

    Route::prefix('dashboard/financial/subscription')->group(function () {
        // Corrigido o nome da rota para "financial.subscription.show"
        Route::get('/', [FinancialSubscriptionController::class, 'index'])->name('financial.subscription.index');
        Route::get('/create', [FinancialSubscriptionController::class, 'create'])->name('financial.subscription.create');
        Route::post('/', [FinancialSubscriptionController::class, 'store'])->name('financial.subscription.store');
        // Corrigido o nome da rota para "financial.subscription.show"
        Route::get('{id}', [FinancialSubscriptionController::class, 'show'])->name('financial.subscription.show');
        Route::get('{id}/edit', [FinancialSubscriptionController::class, 'edit'])->name('financial.subscription.edit');
        Route::put('{id}', [FinancialSubscriptionController::class, 'update'])->name('financial.subscription.update');
        Route::delete('{id}', [FinancialSubscriptionController::class, 'destroy'])->name('financial.subscription.destroy');
    });

    // API Routes for Asaas Integration
    Route::prefix('api/financial/customers')->group(function () {
        Route::get('/', [AsaasController::class, 'index']);
        Route::get('{id}', [AsaasController::class, 'show']);
        Route::post('/', [AsaasController::class, 'store']);
        Route::put('{id}', [AsaasController::class, 'update']);
        Route::delete('{id}', [AsaasController::class, 'destroy']);
    });

    Route::prefix('api/financial/billing')->group(function () {
        Route::get('/', [AsaasBillingController::class, 'index']);
        Route::post('/', [AsaasBillingController::class, 'store']);
        Route::get('{id}', [AsaasBillingController::class, 'show']);
        Route::put('{id}', [AsaasBillingController::class, 'update']);
        Route::delete('{id}', [AsaasBillingController::class, 'destroy']);
    });

    Route::prefix('api/financial/subscription')->group(function () {
        Route::get('/', [AsaasSubscriptionController::class, 'index']);
        Route::post('/', [AsaasSubscriptionController::class, 'store']);
        Route::get('{id}', [AsaasSubscriptionController::class, 'show']);
        Route::put('{id}', [AsaasSubscriptionController::class, 'update']);
        Route::delete('{id}', [AsaasSubscriptionController::class, 'destroy']);
    });
});

require __DIR__.'/auth.php';