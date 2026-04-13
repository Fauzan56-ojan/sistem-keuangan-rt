<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $jenis = request('jenis', 'all');
        $bulan = request('bulan', 'all');
        $tahun = request('tahun', date('Y'));

        $tahunList = collect([
            ...Pemasukan::selectRaw('YEAR(tanggal) as tahun')->pluck('tahun'),
            ...Pengeluaran::selectRaw('YEAR(tanggal) as tahun')->pluck('tahun'),
            ...Pembayaran::selectRaw('YEAR(paid_at) as tahun')->pluck('tahun'),
        ])->filter()->unique()->sortDesc()->values();

        $pemasukanManual = Pemasukan::sum('nominal');
        $pemasukanIuran = Pembayaran::where('status', 'success')->sum('amount');
        $totalPemasukan = $pemasukanManual + $pemasukanIuran;
        $totalPengeluaran = Pengeluaran::sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $pemasukanQuery = Pemasukan::whereYear('tanggal', $tahun);

        if ($bulan != 'all') {
            $pemasukanQuery->whereMonth('tanggal', $bulan);
        }

        $pemasukan = $pemasukanQuery->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'pemasukan',
                'keterangan' => $item->keterangan,
                'masuk' => $item->nominal,
                'keluar' => 0,
            ];
        });

        $pembayaranQuery = Pembayaran::with('user')
            ->where('status', 'success')
            ->whereYear('paid_at', $tahun);

        if ($bulan != 'all') {
            $pembayaranQuery->whereMonth('paid_at', $bulan);
        }

        $pembayaran = $pembayaranQuery->get()->map(function ($item) {
            return [
                'tanggal' => $item->paid_at,
                'jenis' => 'iuran',
                'keterangan' => 'Iuran ' . $item->user->name,
                'masuk' => $item->amount,
                'keluar' => 0,
            ];
        });

        $pengeluaranQuery = Pengeluaran::whereYear('tanggal', $tahun);

        if ($bulan != 'all') {
            $pengeluaranQuery->whereMonth('tanggal', $bulan);
        }

        $pengeluaran = $pengeluaranQuery->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'pengeluaran',
                'keterangan' => $item->keterangan,
                'masuk' => 0,
                'keluar' => $item->nominal,
            ];
        });

        $transaksi = $pemasukan
            ->concat($pembayaran)
            ->concat($pengeluaran)
            ->sortBy('tanggal')
            ->values();

        if ($jenis !== 'all') {
            $transaksi = $transaksi->where('jenis', $jenis)->values();
        }

        if ($jenis == 'iuran') {
            $totalPemasukan = $transaksi->sum('masuk');
            $totalPengeluaran = 0;
        }

        if ($jenis == 'pemasukan') {
            $totalPemasukan = $transaksi->sum('masuk');
            $totalPengeluaran = 0;
        }

        if ($jenis == 'pengeluaran') {
            $totalPemasukan = 0;
            $totalPengeluaran = $transaksi->sum('keluar');
        }

        $saldoBerjalan = 0;

        $transaksi = $transaksi->map(function ($item) use (&$saldoBerjalan) {
            $saldoBerjalan += $item['masuk'] - $item['keluar'];
            $item['saldo'] = $saldoBerjalan;
            return $item;
        });

        return view('laporan.index', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'transaksi',
            'jenis',
            'bulan',
            'tahun',
            'tahunList'
        ));
    }

    public function exportPdf()
    {
        $jenis = request('jenis', 'all');
        $bulan = request('bulan', 'all');
        $tahun = request('tahun', date('Y'));

        $pemasukanManual = Pemasukan::sum('nominal');
        $pemasukanIuran = Pembayaran::where('status', 'success')->sum('amount');

        $totalPemasukan = $pemasukanManual + $pemasukanIuran;

        $totalPengeluaran = Pengeluaran::sum('nominal');

        $saldo = $totalPemasukan - $totalPengeluaran;

        $pemasukanQuery = Pemasukan::whereYear('tanggal', $tahun);
        if ($bulan != 'all') {
            $pemasukanQuery->whereMonth('tanggal', $bulan);
        }

        $pemasukan = $pemasukanQuery->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'pemasukan',
                'keterangan' => $item->keterangan,
                'masuk' => $item->nominal,
                'keluar' => 0,
            ];
        });

        $pembayaranQuery = Pembayaran::with('user')
            ->where('status', 'success')
            ->whereYear('paid_at', $tahun);

        if ($bulan != 'all') {
            $pembayaranQuery->whereMonth('paid_at', $bulan);
        }

        $pembayaran = $pembayaranQuery->get()->map(function ($item) {
            return [
                'tanggal' => $item->paid_at ?? $item->created_at,
                'jenis' => 'iuran',
                'keterangan' => 'Iuran ' . $item->user->name,
                'masuk' => $item->amount,
                'keluar' => 0,
            ];
        });

        $pengeluaranQuery = Pengeluaran::whereYear('tanggal', $tahun);
        if ($bulan != 'all') {
            $pengeluaranQuery->whereMonth('tanggal', $bulan);
        }

        $pengeluaran = $pengeluaranQuery->get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'pengeluaran',
                'keterangan' => $item->keterangan,
                'masuk' => 0,
                'keluar' => $item->nominal,
            ];
        });

        $transaksi = $pemasukan
            ->concat($pembayaran)
            ->concat($pengeluaran)
            ->sortBy('tanggal')
            ->values();

        if ($jenis !== 'all') {
            $transaksi = $transaksi->where('jenis', $jenis)->values();
        }

        $saldoBerjalan = 0;

        $transaksi = $transaksi->map(function ($item) use (&$saldoBerjalan) {
            $saldoBerjalan += $item['masuk'] - $item['keluar'];
            $item['saldo'] = $saldoBerjalan;
            return $item;
        });

        $totalMasuk = $transaksi->sum('masuk');
        $totalKeluar = $transaksi->sum('keluar');

        $pdf = Pdf::loadView('laporan.pdf', [
            'transaksi' => $transaksi,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'jenis' => $jenis,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        return $pdf->download('laporan.pdf');
    }
}