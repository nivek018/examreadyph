<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ExamCategoryController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/reviewers', [HomeController::class, 'reviewers'])->name('reviewers');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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

    // User Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggleBan');
    Route::post('users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.makeAdmin');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
