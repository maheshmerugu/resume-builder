<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\AtsCheckController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeAiController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ResumeFromJdController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/favicon.ico', function () {
    return response(file_get_contents(public_path('favicon.svg')), 200, [
        'Content-Type' => 'image/svg+xml',
        'Cache-Control' => 'public, max-age=604800',
    ]);
});

Route::get('/', function () {
    $plans = \App\Models\Plan::where('is_active', true)->paid()->orderBy('sort_order')->get();
    $themeCount = \App\Support\ResumeThemes::count();

    return view('welcome', compact('plans', 'themeCount'));
})->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Admin login (separate from user login)
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');
});
Route::post('admin/logout', [AdminLoginController::class, 'destroy'])->middleware('auth')->name('admin.logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');

    // Resume builder
    Route::get('resumes/from-job/create', [ResumeFromJdController::class, 'create'])->name('resumes.from-jd.create');
    Route::post('resumes/from-job', [ResumeFromJdController::class, 'store'])->name('resumes.from-jd.store');
    Route::post('resumes/ai/generate', [ResumeAiController::class, 'generate'])->name('resumes.ai.generate');
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

    // Razorpay payment verification
    Route::post('plans/{plan}/verify-payment', [PlanController::class, 'verifyPayment'])->name('plans.verifyPayment');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
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
