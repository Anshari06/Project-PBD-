<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageUserController;

// dashboard route handled by controller
Route::get('/', [DashboardController::class, 'index']);

Route::get('/login', function () {
    return view('login');
});

// Manage user
Route::get('/manage_user', [ManageUserController::class, 'index']);

// Manage Barang route
Route::get('/manage_barang', [App\Http\Controllers\BarangController::class, 'index']);

// Manage Satuan route
Route::get('/manage_satuan', [App\Http\Controllers\SatuanController::class, 'index']);
// Route::post('/manage_satuan', [App\Http\Controllers\SatuanController::class, 'store']);