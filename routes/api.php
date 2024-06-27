<?php

use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/user/{id}/completion-rate', [\App\Http\Controllers\UserProfileController::class, 'getCompletionRate']);

