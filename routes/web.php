<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\AdminDinasController;
use App\Http\Controllers\AdminBidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;

// ==========================================
// RUTE MASYARAKAT (Terbuka)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/pengaduan/buat', [MasyarakatController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan/simpan', [MasyarakatController::class, 'store'])->name('pengaduan.store');
Route::get('/pengaduan/cari', [MasyarakatController::class, 'search'])->name('pengaduan.search');

// ==========================================
// RUTE AUTHENTIKASI
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// RUTE DASHBOARD & ADMIN (Wajib Login & Wajib Aktif)
// ==========================================
Route::middleware(['auth', 'verified', 'checkStatus'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Admin Dinas
    Route::middleware(['role:admin'])->prefix('admin-dinas')->group(function (){
        Route::get('/dashboard', [App\Http\Controllers\AdminDinasController::class, 'dashboard'])->name('admin.dinas.dashboard');
        Route::get('/kelola', [AdminDinasController::class, 'index'])->name('admin_dinas.kelola');

        // Rute Manajemen User
        Route::get('/users', [ManajemenUserController::class, 'index'])->name('admin.manajemen.user');
        Route::post('/users/store', [ManajemenUserController::class, 'store'])->name('admin.user.store');
        Route::put('/users/update/{id}', [ManajemenUserController::class, 'update'])->name('admin.user.update');
        Route::delete('/users/delete/{id}', [ManajemenUserController::class, 'destroy'])->name('admin.user.destroy');

        // Fitur Kelola Pengaduan (Admin Dinas kini juga memproses alih kategori di sini)
        Route::get('/export-pdf', [AdminDinasController::class, 'exportPdf'])->name('admin_dinas.export.pdf');
        Route::get('/export-excel', [AdminDinasController::class, 'exportExcel'])->name('admin_dinas.export.excel');
        Route::put('/update-status/{id}', [AdminDinasController::class, 'updateStatus'])->name('admin_dinas.update');
    });

    // Rute Admin Bidang
    Route::prefix('admin-bidang')->group(function () {
        Route::get('/dashboard', [AdminBidangController::class, 'dashboard'])->name('admin.bidang.dashboard');
        Route::get('/tindaklanjuti', [AdminBidangController::class, 'index'])->name('admin_bidang.tindaklanjuti');

        // Rute untuk update status progres lapangan oleh Admin Bidang
        Route::put('/update/{id}', [AdminBidangController::class, 'update'])->name('admin_bidang.update');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
