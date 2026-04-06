<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPembayaran = DB::table('pembayaran')
        ->where('status', 'success')
        ->sum('amount');

        $totalPemasukan = $totalPembayaran + DB::table('pemasukan')->sum('nominal');
        $totalPengeluaran = DB::table('pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldo'
        ));
    }
}