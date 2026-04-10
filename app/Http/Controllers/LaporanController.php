<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;

class LaporanController extends Controller
{
    public function index()
    {
        $pemasukanManual = Pemasukan::sum('nominal');

        $pemasukanIuran = Pembayaran::where('status', 'success')->sum('amount');

        $totalPemasukan = $pemasukanManual + $pemasukanIuran;

        $totalPengeluaran = Pengeluaran::sum('nominal');

        $saldo = $totalPemasukan - $totalPengeluaran;

        $pemasukan = Pemasukan::get()->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'jenis' => 'pemasukan',
                'keterangan' => $item->keterangan,
                'masuk' => $item->nominal,
                'keluar' => 0,
            ];
        });

        $pembayaran = Pembayaran::with('user')
            ->where('status', 'success')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->paid_at,
                    'jenis' => 'iuran',
                    'keterangan' => 'Iuran ' . $item->user->name,
                    'masuk' => $item->amount,
                    'keluar' => 0,
                ];
            });

        $pengeluaran = Pengeluaran::get()->map(function ($item) {
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
            'transaksi'
        ));
    }
}