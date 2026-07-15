<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\AtsCheckController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $plans = \App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get();

    return view('welcome', compact('plans'));
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resume builder
    Route::get('resumes/{resume}/pdf', [ResumeController::class, 'pdf'])->name('resumes.pdf');
    Route::post('resumes/{resume}/duplicate', [ResumeController::class, 'duplicate'])->name('resumes.duplicate');
    Route::resource('resumes', ResumeController::class);

    // ATS checker
    Route::get('ats', [AtsCheckController::class, 'index'])->name('ats.index');
    Route::get('ats/create', [AtsCheckController::class, 'create'])->name('ats.create');
    Route::post('ats', [AtsCheckController::class, 'store'])->name('ats.store');
    Route::get('ats/{ats}', [AtsCheckController::class, 'show'])->name('ats.show');
    Route::delete('ats/{ats}', [AtsCheckController::class, 'destroy'])->name('ats.destroy');

    // Plans & subscriptions
    Route::get('plans', [PlanController::class, 'index'])->name('plans.index');
    Route::get('plans/{plan}/checkout', [PlanController::class, 'checkout'])->name('plans.checkout');
    Route::post('plans/{plan}/subscribe', [PlanController::class, 'subscribe'])->name('plans.subscribe');
    Route::post('subscription/cancel', [PlanController::class, 'cancel'])->name('plans.cancel');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggleAdmin');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');

    Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/create', [AdminPlanController::class, 'create'])->name('plans.create');
    Route::post('/plans', [AdminPlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [AdminPlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [AdminPlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [AdminPlanController::class, 'destroy'])->name('plans.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
