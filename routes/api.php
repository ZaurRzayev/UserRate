<?php


// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;

Route::get('/user/{id}/completion-rate', [UserProfileController::class, 'getCompletionRate']);


Route::post('/update-percentage', [UserProfileController::class, 'updatePercentage']);






