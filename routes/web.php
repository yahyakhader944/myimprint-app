<?php

use App\Helpers\AuthHelper;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\InvestorProfileController;
use App\Http\Controllers\InvestorSearchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkerProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // If user is logged in, redirect it to correct view
    return AuthHelper::redirectUserByRole(Auth::user());
})->middleware('auth');

// User Settings
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Worker profile routes
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:worker|admin'])->group(function () {
        Route::get('/worker-profiles/create', [WorkerProfileController::class, 'create'])->name('worker-profiles.create');
        Route::post('/worker-profiles', [WorkerProfileController::class, 'store'])->name('worker-profiles.store');
        Route::get('/worker-profiles/{workerProfile}/edit', [WorkerProfileController::class, 'edit'])->name('worker-profiles.edit');
        Route::put('/worker-profiles/{workerProfile}', [WorkerProfileController::class, 'update'])->name('worker-profiles.update');
    });

    Route::get('/worker-profiles/{workerProfile}', [WorkerProfileController::class, 'show'])->name('worker-profiles.show');
    Route::get('/worker-profiles', [WorkerProfileController::class, 'index'])->name('worker-profiles.index');
    Route::delete('/worker-profiles/{workerProfile}', [WorkerProfileController::class, 'destroy'])->name('worker-profiles.destroy')
        ->middleware('role:admin');
});

// Investor profile routes
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:investor|admin'])->group(function () {
        Route::get('/investor-profiles/create', [InvestorProfileController::class, 'create'])->name('investor-profiles.create');
        Route::post('/investor-profiles', [InvestorProfileController::class, 'store'])->name('investor-profiles.store');
        Route::get('/investor-profiles/{investorProfile}/edit', [InvestorProfileController::class, 'edit'])->name('investor-profiles.edit');
        Route::put('/investor-profiles/{investorProfile}', [InvestorProfileController::class, 'update'])->name('investor-profiles.update');
        Route::get('/investor/workers', [InvestorSearchController::class, 'index'])->name('investor.workers.index');
    });

    Route::get('/investor-profiles/{investorProfile}', [InvestorProfileController::class, 'show'])->name('investor-profiles.show');
    Route::get('/investor-profiles', [InvestorProfileController::class, 'index'])->name('investor-profiles.index');
    Route::delete('/investor-profiles/{investorProfile}', [InvestorProfileController::class, 'destroy'])->name('investor-profiles.destroy')
        ->middleware('role:admin');
});

// Conversations & Messages routes
Route::middleware('auth')->group(function () {
    // Conversations
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');

    // Messages inside a conversation
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/conversations/{conversation}/messages/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

require __DIR__ . '/auth.php';
