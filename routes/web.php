<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageUserController;

// dashboard route handled by controller
Route::get('/', [DashboardController::class, 'index']);

// Manage user list via controller so the view receives the $users data
Route::get('/manage_user', [ManageUserController::class, 'index']);
