<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\AuthLoginController;

// dashboard route handled by controller

Route::get('/', [AuthLoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthLoginController::class, 'showLoginForm'])->name('login.form');

Route::post('/login', [AuthLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthLoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'cekrole:1'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Manage user
    Route::get('/manage_user', [ManageUserController::class, 'index']);
    // Manage Barang route
    Route::get('/manage_barang', [App\Http\Controllers\BarangController::class, 'index']);
    // Manage Satuan route
    Route::get('/manage_satuan', [App\Http\Controllers\SatuanController::class, 'index']);
    // Manage Vendor
    Route::get('/manage_vendor', [App\Http\Controllers\VendorController::class, 'index']);
    // Manage Margin
    Route::get('/manage_margin', [App\Http\Controllers\MarginController::class, 'index'])->name('margin.manage_margin');
    // Manage Penjualan
    Route::get('/manage_penjualan', [App\Http\Controllers\PenjualanController::class, 'index']);
    // Detail Penjualan
    Route::get('/detail_penjualan', [App\Http\Controllers\DetailPenjualanController::class, 'index']);
    // Manage Penerimaan(CRUD)
    Route::get('/manage_penerimaan', [App\Http\Controllers\PenerimaanController::class, 'index']);
    Route::post('/manage_penerimaan', [App\Http\Controllers\PenerimaanController::class, 'store'])->name('penerimaan.store');
    // Detail Penerimaan
    Route::get('/detail_penerimaan', [App\Http\Controllers\DetailPenerimaanController::class, 'index']);
    // Manage Pengadaan (CRUD)
    Route::get('/manage_pengadaan', [App\Http\Controllers\PengadaanController::class, 'index'])->name('pengadaan.manage_pengadaan');
    Route::get('/detail_pengadaan/{id}', [App\Http\Controllers\PengadaanController::class, 'show'])->name('pengadaan.detail_pengadaan');
    Route::post('/manage_pengadaan', [App\Http\Controllers\PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::delete('/delete_pengadaan/{id}', [App\Http\Controllers\PengadaanController::class, 'destroy'])->name('pengadaan.destroy');
    // Manage Kartu Stok
    Route::get('/manage_kartu_stok', [App\Http\Controllers\KartuStok::class, 'index'])->name('kartu_stok.kartu_stok');
});
