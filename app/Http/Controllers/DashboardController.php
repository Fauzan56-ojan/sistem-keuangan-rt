<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use App\Services\IuranService;
use App\Models\Pembayaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\Iuran;


class DashboardController extends Controller
{
    public function index(IuranService $service)
    {
        $bulan = date('m');
        $tahun = date('Y'); 

        // card top
        $totalPembayaran = DB::table('pembayaran')
            ->where('status', 'success')
            ->sum('amount');
        $totalPemasukan = $totalPembayaran + DB::table('pemasukan')->sum('nominal');
        $totalPengeluaran = DB::table('pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $totalPembayaranBulan = DB::table('pembayaran')
            ->where('status', 'success')
            ->whereMonth('paid_at', $bulan)
            ->whereYear('paid_at', $tahun)
            ->sum('amount');
        $totalPemasukanBulan = $totalPembayaranBulan + DB::table('pemasukan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');
        $totalPengeluaranBulan = DB::table('pengeluaran')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('nominal');
        $dataTunggakan = $service->getTunggakan();
        $totalTunggakan = collect($dataTunggakan)->sum('total');

        //stats cashflow 6 bulan terakhir
        $cashflow = [];
        $now = now()->startOfMonth(); 

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i); 

            $bulanLoop = $date->month;
            $tahunLoop = $date->year;

            $pemasukan = DB::table('pembayaran')
                ->where('status', 'success')
                ->whereMonth('paid_at', $bulanLoop)
                ->whereYear('paid_at', $tahunLoop)
                ->sum('amount');

            $pemasukan += DB::table('pemasukan')
                ->whereMonth('tanggal', $bulanLoop)
                ->whereYear('tanggal', $tahunLoop)
                ->sum('nominal');

            $pengeluaran = DB::table('pengeluaran')
                ->whereMonth('tanggal', $bulanLoop)
                ->whereYear('tanggal', $tahunLoop)
                ->sum('nominal');

            $cashflow[] = [
                'bulan' => $date->translatedFormat('M'),
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran
            ];
        }

        //presentase yg sudah bayar
        $summary = $service->getSummaryBulanIni();


        // tunggakan warga
        $dataTunggakan = $service->getTunggakan()->take(5);

        // transaksi terakhir
        $pembayaranTerakhir = Pembayaran::with('user')
            ->where('status', 'success')
            ->latest('paid_at')
            ->first();

        $pemasukanTerakhir = Pemasukan::latest('tanggal')->first();

        $pengeluaranTerakhir = Pengeluaran::latest('tanggal')->first();

        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'totalTunggakan',
            'pembayaranTerakhir',
            'pemasukanTerakhir',
            'pengeluaranTerakhir',
            'dataTunggakan',
            'totalPemasukanBulan',
            'totalPengeluaranBulan',
            'cashflow'
        ) + $summary);
    }
}