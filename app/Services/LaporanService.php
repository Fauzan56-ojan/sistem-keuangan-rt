<?php

namespace App\Services;

use App\Models\Pemasukan;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;

class LaporanService
{
    public static function getTransaksi($jenis, $bulan, $tahun)
    {
        $pemasukanQuery = Pemasukan::query();

        if ($tahun != 'all') {
            $pemasukanQuery->whereYear('tanggal', $tahun);
        }

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
            ->where('status', 'success');

        if ($tahun != 'all') {
            $pembayaranQuery->whereYear('paid_at', $tahun);
        }

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

        $pengeluaranQuery = Pengeluaran::query();

        if ($tahun != 'all') {
            $pengeluaranQuery->whereYear('tanggal', $tahun);
        }

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

        return $transaksi->map(function ($item) use (&$saldoBerjalan) {
            $saldoBerjalan += $item['masuk'] - $item['keluar'];
            $item['saldo'] = $saldoBerjalan;
            return $item;
        });
    }

    public static function getSummary($jenis, $transaksi)
    {
        $totalPemasukan = $transaksi->sum('masuk');
        $totalPengeluaran = $transaksi->sum('keluar');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return [
            'totalPemasukan' => $transaksi->sum('masuk'),
            'totalPengeluaran' => $transaksi->sum('keluar'),
            'saldo' => $transaksi->sum('masuk') - $transaksi->sum('keluar')
        ];
    }
}