<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Iuran;
use App\Models\Pembayaran;

class IuranService
{
    public function getTunggakan()
    {
        $bulanSekarang = date('m');
        $tahunSekarang = date('Y');

        $iuran = Iuran::with('user')
            ->whereHas('user', function ($q) {
                $q->where('status_aktif', 1);
            })
            ->where('periode_tahun', $tahunSekarang)
            ->where('periode_bulan', '<', $bulanSekarang)
            ->where('status', 'pending')
            ->get();

        return $iuran->groupBy('user_id')->map(function ($items) {
            return [
                'nama' => $items->first()->user->name,
                'nomor_rumah' => $items->first()->user->nomor_rumah,
                'jumlah_bulan' => $items->count(),
                'total' => $items->sum('nominal'),
                'user_id' => $items->first()->user_id
            ];
        })
        ->sortByDesc('jumlah_bulan')
        ->values();
    }

    public function getTunggakanDetail($id)
    {
        $nowYear = now()->year;
        $nowMonth = now()->month;

        return Iuran::where('user_id', $id)
            ->where('status', 'pending')
            ->where(function ($q) use ($nowYear, $nowMonth) {

                $q->where('periode_tahun', '<', $nowYear)

                ->orWhere(function ($q2) use ($nowYear, $nowMonth) {
                    $q2->where('periode_tahun', $nowYear)
                        ->where('periode_bulan', '<', $nowMonth);
                });

            })
            ->orderBy('periode_tahun')
            ->orderBy('periode_bulan')
            ->get();
    }

    public function generate($tahun)
    {
        $cek = DB::table('iuran')
            ->where('periode_tahun', $tahun)
            ->exists();

        if ($cek) {
            return ['error' => 'Tagihan iuran tahun ini sudah dibuat'];
        }

        $cekNominal = DB::table('setnominal')
            ->where('tahun', $tahun)
            ->exists();

        if (!$cekNominal) {

            $nominalTerakhir = DB::table('setnominal')
                ->orderBy('created_at', 'desc')
                ->value('nominal');

            if (!$nominalTerakhir) {
                return ['error' => 'Nominal sebelumnya belum ada'];
            }

            DB::table('setnominal')->insert([
                'tahun' => $tahun,
                'bulan' => 1,
                'nominal' => $nominalTerakhir,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $users = User::where('status_aktif', 1)
            ->where('role', 'warga')
            ->get();

        foreach ($users as $user) {

            for ($bulan = 1; $bulan <= 12; $bulan++) {

                $nominal = DB::table('setnominal')
                    ->where('tahun', $tahun)
                    ->where('bulan', '<=', $bulan)
                    ->orderBy('bulan', 'desc')
                    ->value('nominal');

                if (!$nominal) {
                    return ['error' => "Nominal belum diset sampai bulan $bulan"];
                }

                Iuran::create([
                    'user_id' => $user->id,
                    'periode_bulan' => $bulan,
                    'periode_tahun' => $tahun,
                    'nominal' => $nominal,
                    'status' => 'pending'
                ]);
            }
        }

        return ['success' => 'Tagihan iuran berhasil dibuat'];
    }

    public function migrasi($tahun, $bulanAkhir)
    {
        $users = User::where('status_aktif', 1)
            ->where('role', 'warga')
            ->get();

        foreach ($users as $user) {

            for ($i = 1; $i <= $bulanAkhir; $i++) {

                $cek = Iuran::where('user_id', $user->id)
                    ->where('periode_tahun', $tahun)
                    ->where('periode_bulan', $i)
                    ->exists();

                if ($cek) {
                    continue;
                }

                $nominal = \DB::table('setnominal')
                    ->where('tahun', $tahun)
                    ->where('bulan', '<=', $i)
                    ->orderBy('bulan', 'desc')
                    ->value('nominal');

                if (!$nominal) {
                    return ['error' => "Nominal belum diset sampai bulan $i"];
                }

                Iuran::create([
                    'user_id' => $user->id,
                    'periode_bulan' => $i,
                    'periode_tahun' => $tahun,
                    'nominal' => $nominal,
                    'status' => 'pending'
                ]);
            }
        }

        return ['success' => 'Data tagihan periode sebelumnya berhasil dibuat'];
    }

    public function getDataWarga($id, $tahun)
    {
        $iuran = Iuran::where('user_id', $id)
            ->where('periode_tahun', $tahun)
            ->orderBy('periode_bulan')
            ->get();

        $pembayaran = Pembayaran::whereIn('iuran_id', $iuran->pluck('id'))
            ->latest('paid_at')
            ->get()
            ->keyBy('iuran_id');

        $iuran = $iuran->map(function($item) use ($pembayaran) {
            $item->pembayaran = $pembayaran[$item->id] ?? null;
            return $item;
        });

        $tahunList = Iuran::where('user_id', $id)
            ->select('periode_tahun')
            ->distinct()
            ->orderByDesc('periode_tahun')
            ->pluck('periode_tahun');

        $lastPaid = $iuran
            ->where('status', 'paid')
            ->sortByDesc('periode_bulan')
            ->first();

        return ['iuran' => $iuran, 'tahunList' => $tahunList, 'lastPaid' => $lastPaid
        ];
    }

    public function getSummaryBulanIni()
    {
        $bulan = now()->month;
        $tahun = now()->year;
        $totalWarga = User::where('role', 'warga')
            ->where('status_aktif', 1)
            ->count();

       $sudahBayar = Iuran::whereHas('user', function ($q) {
             $q->where('status_aktif', 1);
            })
            ->where('periode_bulan', now()->month)
            ->where('periode_tahun', now()->year)
            ->where('status', 'paid')
            ->count();

        $belumBayar = Iuran::whereHas('user', function ($q) {
                $q->where('status_aktif', 1);
            })
            ->where('periode_bulan', now()->month)
            ->where('periode_tahun', now()->year)
            ->where('status', 'pending')
            ->count();

        $persen = $totalWarga > 0
            ? round(($sudahBayar / $totalWarga) * 100)
            : 0;

        return compact('totalWarga','sudahBayar','belumBayar','persen');
    }
}