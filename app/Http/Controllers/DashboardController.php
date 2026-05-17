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
        $allTunggakan = $service->getTunggakan();

$totalTunggakanNominal = $allTunggakan->sum('total'); 

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
        $sudahBayarBulanIni = false;
        $nominalBulanIni = 0;
        $jumlahTunggakan = 0;
        $detailTunggakan = collect();
        $dataTunggakan = collect();
        $totalTunggakanOrang = 0;

        if (auth()->user()->role === 'warga') {

            $userId = auth()->id();

            // tunggakan (bulan sebelumnya)
            $detailTunggakan = $service->getTunggakanDetail($userId);
            $jumlahTunggakan = $detailTunggakan->count();

            // status bulan ini
            $iuranBulanIni = Iuran::where('user_id', $userId)
                ->where('periode_bulan', now()->month)
                ->where('periode_tahun', now()->year)
                ->first();

            $sudahBayarBulanIni = $iuranBulanIni && $iuranBulanIni->status === 'Lunas';
            $nominalBulanIni = $iuranBulanIni->nominal ?? 0;

        } else {

            $allTunggakan = $service->getTunggakan();

            $dataTunggakan = $allTunggakan->take(5);
            $totalTunggakanOrang = $allTunggakan->count();
        }

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
            'totalTunggakanNominal',
            'totalTunggakanOrang',
            'pembayaranTerakhir',
            'pemasukanTerakhir',
            'pengeluaranTerakhir',
            'dataTunggakan',
            'totalPemasukanBulan',
            'totalPengeluaranBulan',
            'cashflow',
            'jumlahTunggakan',
            'detailTunggakan',
            'sudahBayarBulanIni',
            'nominalBulanIni',
        ) + $summary);
    }
}