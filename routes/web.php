<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Professional\DashboardController as ProfessionalDashboard;

// ── Guest routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', fn() => redirect()->route('home'));

// ── Home (semua role) ─────────────────────────────────────────────────────────
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// ── User routes ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/checkin',  [\App\Http\Controllers\User\CheckInController::class, 'store'])->name('user.checkin');
    Route::get('/progress',  [\App\Http\Controllers\User\ProgressController::class, 'index'])->name('user.progress');
    Route::post('/progress', [\App\Http\Controllers\User\ProgressController::class, 'store']);

    Route::get('/contents',           [\App\Http\Controllers\ContentController::class, 'index'])->name('contents.index');
    Route::get('/contents/create',    [\App\Http\Controllers\ContentController::class, 'create'])->name('contents.create');
    Route::post('/contents',          [\App\Http\Controllers\ContentController::class, 'store'])->name('contents.store');
    Route::get('/contents/{content}', [\App\Http\Controllers\ContentController::class, 'show'])->name('contents.show');

    Route::get('/books',              [\App\Http\Controllers\BookController::class, 'index'])->name('books.index');
    Route::get('/my-books',           [\App\Http\Controllers\BookController::class, 'purchased'])->name('books.purchased');
    Route::get('/books/{book}/read',  [\App\Http\Controllers\BookController::class, 'read'])->name('books.read');
    Route::get('/books/{book}',       [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');
    Route::post('/books/{book}/order',[\App\Http\Controllers\BookController::class, 'order'])->name('books.order');

    Route::get('/consultations',                      [\App\Http\Controllers\ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/my-appointments',                    [\App\Http\Controllers\ConsultationController::class, 'appointments'])->name('consultations.appointments');
    Route::get('/consultations/{professional}',       [\App\Http\Controllers\ConsultationController::class, 'show'])->name('consultations.show');
    Route::post('/consultations/{professional}/book', [\App\Http\Controllers\ConsultationController::class, 'book'])->name('consultations.book');

    Route::get('/payments',                 [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}',       [\App\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/pay',  [\App\Http\Controllers\PaymentController::class, 'pay'])->name('payments.pay');

    Route::get('/forums',                 [\App\Http\Controllers\ForumController::class, 'index'])->name('forums.index');
    Route::post('/forums',                [\App\Http\Controllers\ForumController::class, 'store'])->name('forums.store');
    Route::get('/forums/{forum}',         [\App\Http\Controllers\ForumController::class, 'show'])->name('forums.show');
    Route::post('/forums/{forum}/reply',  [\App\Http\Controllers\ForumController::class, 'reply'])->name('forums.reply');

    Route::get('/notifications',  [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/profile/edit',   [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',        [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users',                  [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit',      [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',           [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',        [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Professionals
    Route::get('/professionals',                         [\App\Http\Controllers\Admin\ProfessionalController::class, 'index'])->name('professionals');
    Route::get('/professionals/{professional}',          [\App\Http\Controllers\Admin\ProfessionalController::class, 'show'])->name('professionals.show');
    Route::get('/professionals/{professional}/edit',     [\App\Http\Controllers\Admin\ProfessionalController::class, 'edit'])->name('professionals.edit');
    Route::put('/professionals/{professional}',          [\App\Http\Controllers\Admin\ProfessionalController::class, 'update'])->name('professionals.update');
    Route::delete('/professionals/{professional}',       [\App\Http\Controllers\Admin\ProfessionalController::class, 'destroy'])->name('professionals.destroy');
    Route::post('/professionals/{professional}/approve', [\App\Http\Controllers\Admin\ProfessionalController::class, 'approve'])->name('professionals.approve');
    Route::post('/professionals/{professional}/reject',  [\App\Http\Controllers\Admin\ProfessionalController::class, 'reject'])->name('professionals.reject');

    // Contents
    Route::get('/contents',                    [\App\Http\Controllers\Admin\ContentController::class, 'index'])->name('contents');
    Route::get('/contents/{content}/edit',     [\App\Http\Controllers\Admin\ContentController::class, 'edit'])->name('contents.edit');
    Route::put('/contents/{content}',          [\App\Http\Controllers\Admin\ContentController::class, 'update'])->name('contents.update');
    Route::delete('/contents/{content}',       [\App\Http\Controllers\Admin\ContentController::class, 'destroy'])->name('contents.destroy');
    Route::post('/contents/{content}/approve', [\App\Http\Controllers\Admin\ContentController::class, 'approve'])->name('contents.approve');
    Route::post('/contents/{content}/reject',  [\App\Http\Controllers\Admin\ContentController::class, 'reject'])->name('contents.reject');

    // Forums
    Route::get('/forums',                           [\App\Http\Controllers\Admin\ForumController::class, 'index'])->name('forums');
    Route::get('/forums/{forum}/edit',              [\App\Http\Controllers\Admin\ForumController::class, 'edit'])->name('forums.edit');
    Route::put('/forums/{forum}',                   [\App\Http\Controllers\Admin\ForumController::class, 'update'])->name('forums.update');
    Route::delete('/forums/{forum}',                [\App\Http\Controllers\Admin\ForumController::class, 'destroy'])->name('forums.destroy');
    Route::delete('/forum-replies/{reply}',         [\App\Http\Controllers\Admin\ForumController::class, 'destroyReply'])->name('forum-replies.destroy');

    // Transactions
    Route::get('/payments',                         [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments');
    Route::get('/payments/{payment}',               [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::patch('/payments/{payment}/status',      [\App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('payments.status');
    Route::get('/orders',                           [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}',                   [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status',          [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/appointments',                     [\App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('appointments');
    Route::patch('/appointments/{appointment}/status', [\App\Http\Controllers\Admin\AppointmentController::class, 'updateStatus'])->name('appointments.status');
});

// ── Professional routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:professional'])->prefix('professional')->name('professional.')->group(function () {
    Route::get('/', [ProfessionalDashboard::class, 'index'])->name('dashboard');

    Route::get('/setup',  [\App\Http\Controllers\Professional\SetupController::class, 'show'])->name('setup');
    Route::post('/setup', [\App\Http\Controllers\Professional\SetupController::class, 'store']);

    Route::get('/appointments',                         [\App\Http\Controllers\Professional\AppointmentController::class, 'index'])->name('appointments');
    Route::post('/appointments/{appointment}/confirm',  [\App\Http\Controllers\Professional\AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('/appointments/{appointment}/complete', [\App\Http\Controllers\Professional\AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::post('/appointments/{appointment}/cancel',   [\App\Http\Controllers\Professional\AppointmentController::class, 'cancel'])->name('appointments.cancel');

    Route::get('/schedule',              [\App\Http\Controllers\Professional\ScheduleController::class, 'index'])->name('schedule');
    Route::post('/schedule',             [\App\Http\Controllers\Professional\ScheduleController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{schedule}',[\App\Http\Controllers\Professional\ScheduleController::class, 'destroy'])->name('schedule.destroy');
});
