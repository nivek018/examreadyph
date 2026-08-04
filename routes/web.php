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
use App\Http\Controllers\Admin\SubtopicController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\AdTrackingController;
use App\Http\Controllers\SubjectPageController;
use App\Http\Controllers\ExamSetupController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\Admin\ForumModerationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/reviewers', [HomeController::class, 'reviewers'])->name('reviewers');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Blog / Study Guides
Route::get('/study-guides', [BlogController::class, 'index'])->name('blog.index');
Route::get('/study-guides/category/{category}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/study-guides/{post}', [BlogController::class, 'show'])->name('blog.show');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Community Forum (authenticated posting routes - MUST BE BEFORE {thread} wildcard)
Route::middleware(['auth', App\Http\Middleware\ForumSpamGuard::class])->group(function () {
    Route::get('/community/new/{category?}', [ForumController::class, 'createThread'])->name('forum.create');
    Route::post('/community/new/{category?}', [ForumController::class, 'storeThread'])->name('forum.store');
    Route::get('/community/{category}/new-thread', [ForumController::class, 'createThread']);
    Route::post('/community/{category}/new-thread', [ForumController::class, 'storeThread']);
    Route::post('/community/{category}/{thread}/reply', [ForumController::class, 'storeReply'])->name('forum.reply');
});

// Community Forum (public actions & viewing)
Route::post('/community/report/{type}/{id}', [ForumController::class, 'report'])->name('forum.report');
Route::post('/community/upvote/{type}/{id}', [ForumController::class, 'toggleUpvote'])->name('forum.upvote');
Route::get('/community', [ForumController::class, 'index'])->name('forum.index');
Route::get('/community/{category}', [ForumController::class, 'category'])->name('forum.category');
Route::get('/community/{category}/{thread}', [ForumController::class, 'show'])->name('forum.show');

// Ad Tracking AJAX endpoints
Route::post('/ads/{ad}/impression', [AdTrackingController::class, 'impression'])->name('ads.impression');
Route::post('/ads/{ad}/click', [AdTrackingController::class, 'click'])->name('ads.click');

// SEO Subject Landing Pages
Route::get('/reviewers/{exam}', [SubjectPageController::class, 'show'])->name('reviewer.show');
Route::get('/reviewers/{exam}/{subtopic}', [SubjectPageController::class, 'subtopic'])->name('reviewer.subtopic');

// Exam Setup & Mode Selection
Route::get('/exam/{exam}/setup', [ExamSetupController::class, 'setup'])->name('exam.setup');
Route::get('/exam/{exam}/practice-setup', [ExamSetupController::class, 'practiceSetup'])->name('exam.practice-setup');
Route::post('/exam/{exam}/start-session', [ExamSetupController::class, 'startSession'])->name('exam.start-session');

// Exam Session Routes (Guest & User accessible)
Route::post('/exam/{exam}/start', [ExamSessionController::class, 'start'])->name('exam.start');
Route::get('/exam/session/{session}', [ExamSessionController::class, 'take'])->name('exam.take');
Route::post('/exam/session/{session}/answer', [ExamSessionController::class, 'answer'])->name('exam.answer');
Route::post('/exam/session/{session}/navigate', [ExamSessionController::class, 'navigate'])->name('exam.navigate');
Route::post('/exam/session/{session}/report-question', [ExamSessionController::class, 'reportQuestion'])->name('exam.reportQuestion');
Route::post('/exam/session/{session}/explain-question', [ExamSessionController::class, 'explainQuestion'])->name('exam.explainQuestion');
Route::post('/exam/session/{session}/submit', [ExamSessionController::class, 'submit'])->name('exam.submit');
Route::get('/exam/session/{session}/results', [ExamSessionController::class, 'results'])->name('exam.results');

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

    // Subtopics CRUD
    Route::resource('subtopics', SubtopicController::class)->except(['show']);

    // Blog Posts CRUD
    Route::resource('blog', BlogPostController::class)->except(['show']);

    // Blog Categories CRUD
    Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);

    // User Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggleBan');
    Route::post('users/{user}/make-admin', [UserController::class, 'makeAdmin'])->name('users.makeAdmin');
    Route::post('users/{user}/toggle-subscription', [UserController::class, 'toggleSubscription'])->name('users.toggleSubscription');

    // Forum Moderation
    Route::get('forum', [ForumModerationController::class, 'index'])->name('forum.index');
    Route::post('forum/reports/{report}/resolve', [ForumModerationController::class, 'resolve'])->name('forum.resolve');
    Route::post('forum/reports/{report}/dismiss', [ForumModerationController::class, 'dismiss'])->name('forum.dismiss');
    Route::post('forum/threads/{thread}/pin', [ForumModerationController::class, 'togglePin'])->name('forum.pin');
    Route::post('forum/threads/{thread}/lock', [ForumModerationController::class, 'toggleLock'])->name('forum.lock');
    Route::post('forum/spam/{type}/{id}', [ForumModerationController::class, 'markSpam'])->name('forum.spam');
    Route::delete('forum/delete/{type}/{id}', [ForumModerationController::class, 'destroy'])->name('forum.destroy');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
