<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LaporanService;

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

        $transaksi = LaporanService::getTransaksi($jenis, $bulan, $tahun);

        $summary = LaporanService::getSummary($jenis, $transaksi);

        return view('laporan.index', [
            'totalPemasukan' => $summary['totalPemasukan'],
            'totalPengeluaran' => $summary['totalPengeluaran'],
            'saldo' => $summary['saldo'],
            'transaksi' => $transaksi,
            'jenis' => $jenis,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tahunList' => $tahunList
        ]);
    }

    public function exportPdf()
    {
        $jenis = request('jenis', 'all');
        $bulan = request('bulan', 'all');
        $tahun = request('tahun', date('Y'));

        $transaksi = LaporanService::getTransaksi($jenis, $bulan, $tahun);

        $summary = LaporanService::getSummary($jenis, $transaksi);

        $pdf = Pdf::loadView('laporan.pdf', [
            'transaksi' => $transaksi,
            'totalPemasukan' => $summary['totalPemasukan'],
            'totalPengeluaran' => $summary['totalPengeluaran'],
            'saldo' => $summary['saldo'],
            'jenis' => $jenis,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);

        return $pdf->download('laporan.pdf');
    }
}