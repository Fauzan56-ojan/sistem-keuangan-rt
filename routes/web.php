<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/iuran-warga', [IuranController::class, 'wargaList']);
    Route::resource('iuran', IuranController::class);
    Route::get('/warga/{id}/iuran', [IuranController::class, 'warga']);

    Route::post('/bayar-tunai/{id}', [PembayaranController::class,'tunai']);
    Route::post('/get-snap-token/{id}', [PembayaranController::class,'getSnapToken']);
    Route::get('/checkout/{id}/{metode}', [PembayaranController::class,'checkout']);

    Route::resource('users', UserController::class);
    Route::get('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
    Route::resource('pemasukan', \App\Http\Controllers\PemasukanController::class);
    Route::resource('pengeluaran', \App\Http\Controllers\PengeluaranController::class);

});

require __DIR__.'/auth.php';