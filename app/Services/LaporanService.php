<?php

namespace App\Services;

use App\Models\Pemasukan;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;

class LaporanService
{
    public static function getTransaksi($jenis, $bulan, $tahun)
    {
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

        return $transaksi->map(function ($item) use (&$saldoBerjalan) {
            $saldoBerjalan += $item['masuk'] - $item['keluar'];
            $item['saldo'] = $saldoBerjalan;
            return $item;
        });
    }

    public static function getSummary($jenis, $transaksi)
    {
        $pemasukanManual = Pemasukan::sum('nominal');
        $pemasukanIuran = Pembayaran::where('status', 'success')->sum('amount');

        $totalPemasukan = $pemasukanManual + $pemasukanIuran;

        $totalPengeluaran = Pengeluaran::sum('nominal');

        $saldo = $totalPemasukan - $totalPengeluaran;

        if ($jenis == 'iuran' || $jenis == 'pemasukan') {
            $totalPemasukan = $transaksi->sum('masuk');
            $totalPengeluaran = 0;
        }

        if ($jenis == 'pengeluaran') {
            $totalPemasukan = 0;
            $totalPengeluaran = $transaksi->sum('keluar');
        }

        return [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo
        ];
    }
}