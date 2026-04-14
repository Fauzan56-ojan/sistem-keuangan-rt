<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NominalController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;


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
    Route::resource('pemasukan', PemasukanController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    
    Route::post('/nominal', [NominalController::class, 'store'])->name('nominal.store');
    Route::post('/iuran/generate', [IuranController::class, 'generate'])->name('iuran.generate');
    Route::get('/settings', function () {return view('settings.index');})->name('settings.index');
    Route::get('/settings/migrasi', function () {return view('settings.migrasi');})->name('settings.migrasi');
    Route::post('/settings/migrasi', [IuranController::class, 'migrasi'])->name('settings.migrasi.proses');
    Route::get('/settings/nominal', [NominalController::class, 'index'])->name('settings.nominal');
    Route::get('/settings/generate', function () {return view('settings.generate');})->name('settings.generate');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/tunggakan', [IuranController::class, 'tunggakan'])->name('tunggakan.index');
    Route::get('/tunggakan/{id}', [IuranController::class, 'tunggakanDetail']);

});

require __DIR__.'/auth.php';