<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\PembayaranController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('iuran', IuranController::class);
Route::get('/warga/{id}/iuran', [IuranController::class, 'warga']);
Route::post('/bayar-tunai/{id}', [PembayaranController::class,'tunai']);
// Route::post('/bayar-online/{id}', [PembayaranController::class,'online']);
Route::post('/get-snap-token/{id}', [PembayaranController::class,'getSnapToken']);
Route::post('/midtrans/webhook', [PembayaranController::class, 'webhook']);
Route::get('/checkout/{id}/{metode}', [PembayaranController::class,'checkout']);
