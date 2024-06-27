<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;

// Routes that require authentication
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{user}', [UserProfileController::class, 'update'])->name('profile.update');
});

// Route that does not require authentication
Route::get('/user/{id}/completion-rate', [UserProfileController::class, 'getCompletionRate']);

// Admin routes (requires both 'auth' and 'admin' middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
});

// Default welcome route
Route::get('/', function () {
    return view('welcome');
});

// Authenticated and verified dashboard route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Include the default Laravel auth routes
require __DIR__.'/auth.php';
