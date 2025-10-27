<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// dashboard route handled by controller
Route::get('/', [DashboardController::class, 'index']);

Route::get('/manage_user', function () {
    return view('manage_user');
});
