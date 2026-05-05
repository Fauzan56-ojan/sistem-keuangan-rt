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
use App\Services\PembayaranService;


Route::get('/', function () {
    return redirect('/login');
});

Route::post('/midtrans/webhook', function () {
    return PembayaranService::handleWebhook();
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', function () {return redirect()->route('settings.profile');});
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/iuran-warga', [IuranController::class, 'wargaList'])->name('iuran.warga')
        ->middleware('role:admin,bendahara,ketua_rt');
    Route::get('/warga/{id}/iuran', [IuranController::class, 'warga']);
    Route::get('/belum-bayar', [IuranController::class, 'belumBayar'])->name('belum.bayar')
    ->middleware('role:admin,bendahara,ketua_rt');

    Route::post('/bayar-tunai/{id}', [PembayaranController::class,'tunai'])
        ->middleware('role:admin,bendahara');
    Route::post('/get-snap-token/{id}', [PembayaranController::class,'getSnapToken']);
    Route::get('/checkout/{id}/{metode}', [PembayaranController::class,'checkout']);

    Route::resource('users', UserController::class)
        ->middleware('role:admin');

    Route::get('/users/{id}/reset-password', [UserController::class, 'resetPassword'])
        ->middleware('role:admin');

    Route::resource('pemasukan', PemasukanController::class);
    Route::resource('pengeluaran', PengeluaranController::class);

    Route::get('/settings', function () {return view('settings.index');})
        ->middleware('role:admin')->name('settings.index');
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('settings.profile');
    Route::post('/settings/migrasi', [IuranController::class, 'migrasi'])
        ->middleware('role:admin')->name('settings.migrasi.proses');
    Route::get('/settings/nominal', [NominalController::class, 'index'])
        ->middleware('role:admin')->name('settings.nominal');
    Route::get('/settings/generate', function () {return view('settings.generate');})
        ->middleware('role:admin')->name('settings.generate');
    Route::post('/nominal', [NominalController::class, 'store'])
        ->middleware('role:admin')->name('nominal.store');
    Route::post('/iuran/generate', [IuranController::class, 'generate'])
        ->middleware('role:admin')->name('iuran.generate');
    Route::get('/settings/migrasi', function () {return view('settings.migrasi');})
        ->middleware('role:admin')->name('settings.migrasi');
    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index')
        ->middleware('role:admin,bendahara,ketua_rt');

    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf')
        ->middleware('role:admin,bendahara,ketua_rt');

    Route::get('/tunggakan/{id}', [IuranController::class, 'tunggakanDetail']);
    Route::get('/tunggakan', [IuranController::class, 'tunggakan'])
        ->middleware('role:admin,bendahara,ketua_rt')->name('tunggakan.index');
    
    Route::get('/riwayat', [PembayaranController::class, 'riwayat'])
        ->middleware('role:admin,bendahara,warga');
    Route::delete('/pembayaran/{id}/batal', [PembayaranController::class, 'batal']);


});

require __DIR__.'/auth.php';