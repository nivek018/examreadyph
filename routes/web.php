<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ExamCategoryController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ReportedQuestionController;
use App\Http\Controllers\Admin\AdPopupController;
use App\Http\Controllers\AdTrackingController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/reviewers', [HomeController::class, 'reviewers'])->name('reviewers');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Ad Tracking AJAX endpoints
Route::post('/ads/{ad}/impression', [AdTrackingController::class, 'impression'])->name('ads.impression');
Route::post('/ads/{ad}/click', [AdTrackingController::class, 'click'])->name('ads.click');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Exam Session Routes
    Route::post('/exam/{exam}/start', [ExamSessionController::class, 'start'])->name('exam.start');
    Route::get('/exam/session/{session}', [ExamSessionController::class, 'take'])->name('exam.take');
    Route::post('/exam/session/{session}/answer', [ExamSessionController::class, 'answer'])->name('exam.answer');
    Route::post('/exam/session/{session}/navigate', [ExamSessionController::class, 'navigate'])->name('exam.navigate');
    Route::post('/exam/session/{session}/report-question', [ExamSessionController::class, 'reportQuestion'])->name('exam.reportQuestion');
    Route::post('/exam/session/{session}/submit', [ExamSessionController::class, 'submit'])->name('exam.submit');
    Route::get('/exam/session/{session}/results', [ExamSessionController::class, 'results'])->name('exam.results');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Exam Categories CRUD
    Route::resource('categories', ExamCategoryController::class)->except(['show']);

    // Exams CRUD
    Route::resource('exams', ExamController::class)->except(['show']);

    // Questions CRUD + Import
    Route::resource('questions', QuestionController::class)->except(['show']);
    Route::post('questions/import', [QuestionController::class, 'import'])->name('questions.import');

    // Reported Questions
    Route::get('reported-questions', [ReportedQuestionController::class, 'index'])->name('reported-questions.index');
    Route::post('reported-questions/{report}/resolve', [ReportedQuestionController::class, 'resolve'])->name('reported-questions.resolve');

    // Ad Campaigns CRUD
    Route::resource('ads', AdPopupController::class)->except(['show']);

    // User Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggleBan');
    Route::post('users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.makeAdmin');
    Route::post('users/{user}/toggle-subscription', [UserController::class, 'toggleSubscription'])->name('users.toggleSubscription');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
