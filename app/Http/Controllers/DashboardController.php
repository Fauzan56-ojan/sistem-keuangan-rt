<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use App\Services\IuranService;


class DashboardController extends Controller
{
    public function index(IuranService $service)
    {
        $totalPembayaran = DB::table('pembayaran')
        ->where('status', 'success')
        ->sum('amount');

        $totalPemasukan = $totalPembayaran + DB::table('pemasukan')->sum('nominal');
        $totalPengeluaran = DB::table('pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $dataTunggakan = $service->getTunggakan();
        $totalTunggakan = collect($dataTunggakan)->sum('total');

        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'totalTunggakan',
        ));
    }
}