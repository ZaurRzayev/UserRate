<?php

use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/profile/completion/{user}', [UserProfileController::class, 'getProfileCompletion']);
