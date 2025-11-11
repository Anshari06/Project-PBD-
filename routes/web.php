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

// Manage Vendor
Route::get('/manage_vendor', [App\Http\Controllers\VendorController::class, 'index']);
// Manage Margin
Route::get('/manage_margin', [App\Http\Controllers\MarginController::class, 'index'])->name('margin.manage_margin');

// Manage Penjualan
Route::get('/manage_penjualan', [App\Http\Controllers\PenjualanController::class, 'index']);
// Detail Penjualan
Route::get('/detail_penjualan', [App\Http\Controllers\DetailPenjualanController::class, 'index']);

// Manage Penerimaan
Route::get('/manage_penerimaan', [App\Http\Controllers\PenerimaanController::class, 'index']);
// Detail Penerimaan
Route::get('/detail_penerimaan', [App\Http\Controllers\DetailPenerimaanController::class, 'index']);
// Manage Pengadaan
Route::get('/manage_pengadaan', [App\Http\Controllers\PengadaanController::class, 'index'])->name('pengadaan.manage_pengadaan');
// detail route must include a slash before the {id} parameter so URLs like /detail_pengadaan/1 match
Route::get('/detail_pengadaan/{id}', [App\Http\Controllers\PengadaanController::class, 'show'])->name('pengadaan.detail_pengadaan');
// POST should call store() to create a new pengadaan
Route::post('/manage_pengadaan', [App\Http\Controllers\PengadaanController::class, 'store'])->name('pengadaan.store');
// DELETE should call destroy() to delete a pengadaan
// Route::delete('/manage_pengadaan/{id}', [App\Http\Controllers\PengadaanController::class, 'destroy'])->name('pengadaan.destroy');