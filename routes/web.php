<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FinancialController; 
use App\Http\Controllers\AsaasController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('dashboard/business', BusinessController::class);
    Route::resource('dashboard/projects', ProjectController::class); 

    Route::get('dashboard/financial', [FinancialController::class, 'index'])->name('financial.index');
    Route::get('dashboard/financial/create', [FinancialController::class, 'create'])->name('financial.create');
    Route::post('dashboard/financial', [FinancialController::class, 'store'])->name('financial.store');
    Route::get('dashboard/financial/{id}', [FinancialController::class, 'show'])->name('financial.show');
    Route::get('dashboard/financial/{id}/edit', [FinancialController::class, 'edit'])->name('financial.edit');

    Route::get('api/financial/customers', [AsaasController::class, 'index']);
    Route::get('/api/financial/customers/{id}', [AsaasController::class, 'show']);
    Route::post('api/financial/customers', [AsaasController::class, 'store']);
    Route::put('api/financial/customers/{id}', [AsaasController::class, 'update']);
    Route::delete('api/financial/customers/{id}', [AsaasController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
