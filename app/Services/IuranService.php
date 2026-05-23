<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        try {

            return DB::transaction(function () use ($tahun) {

                $tahunSekarang = now()->year;

                if ($tahun != $tahunSekarang + 1) {
                    throw new \Exception('Tahun tidak valid, Silakan pilih tahun setelah tahun saat ini');
                }
                $cek = DB::table('iuran')
                    ->where('periode_tahun', $tahun)
                    ->exists();

                if ($cek) {
                    throw new \Exception('Tagihan iuran tahun ini sudah dibuat');
                }

                $cekNominal = DB::table('setnominal')
                    ->where('tahun', $tahun)
                    ->exists();

                if (!$cekNominal) {

                    $nominalTerakhir = DB::table('setnominal')
                        ->orderBy('created_at', 'desc')
                        ->value('nominal');

                    if (!$nominalTerakhir) {
                        throw new \Exception('Nominal sebelumnya belum ada');
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
                            throw new \Exception("Nominal belum diset sampai bulan $bulan");
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
            });

        } catch (\Exception $e) {

            return ['error' => $e->getMessage()];
        }
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

    public static function getHistoriData()
    {
        $searchAktif = request('search_aktif');
        $searchNonaktif = request('search_nonaktif');

        $aktif = User::where('role', 'warga')
            ->where('status_aktif', 1)
            ->when($searchAktif, function ($q) use ($searchAktif) {
                $q->where(function ($q2) use ($searchAktif) {
                    $q2->where('name', 'like', "%$searchAktif%")
                        ->orWhere('nomor_rumah', 'like', "%$searchAktif%");

                });

            })
            ->get();

        $nonaktif = User::where('role', 'warga')
            ->where('status_aktif', 0)
            ->when($searchNonaktif, function ($q) use ($searchNonaktif) {
                $q->where(function ($q2) use ($searchNonaktif) {
                    $q2->where('name', 'like', "%$searchNonaktif%")
                        ->orWhere('nomor_rumah', 'like', "%$searchNonaktif%");
                });
            })
            ->get();

        return [
            'aktif' => $aktif,
            'nonaktif' => $nonaktif
        ];
    }
    
    public static function getHistoriDetail($id)
    {
        $warga = User::findOrFail($id);

        if ($warga->status_aktif == 1) {
            $defaultTahun = now()->year - 1;
        } else {
            $defaultTahun = Iuran::where('user_id', $id)
                ->max('periode_tahun') ?? now()->year - 1;
        }

        $tahun = request('tahun', $defaultTahun);

        $iuran = Iuran::with('pembayaran')
            ->where('user_id', $id)
            ->where('periode_tahun', $tahun)
            ->orderBy('periode_bulan')
            ->get();

        $lastPaid = $iuran
            ->where('status', 'paid')
            ->sortByDesc('periode_bulan')
            ->first();

        $tahunList = [];

        for ($i = now()->year; $i >= 2021; $i--) {
            $tahunList[] = $i;
        }

        return [
            'warga' => $warga,
            'iuran' => $iuran,
            'tahun' => $tahun,
            'tahunList' => $tahunList,
            'lastPaid' => $lastPaid
        ];
    }

    public static function storeHistori($request, $id)
    {
        foreach ($request->bulan as $bulan => $value) {

            if (!$value) {
                continue;
            }

            $sudahAda = Iuran::where('user_id', $id)
                ->where('periode_bulan', $bulan)
                ->where('periode_tahun', $request->tahun)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            $iuran = Iuran::create([
                'user_id' => $id,
                'periode_bulan' => $bulan,
                'periode_tahun' => $request->tahun,
                'nominal' => $request->nominal[$bulan],
                'status' => 'paid'
            ]);

            Pembayaran::create([
                'user_id' => $id,
                'iuran_id' => $iuran->id,
                'amount' => $request->nominal[$bulan],
                'metode' => 'tunai',
                'paid_at' => $request->tanggal[$bulan],
                'status' => 'success'
            ]);
        }
    }

    public static function storeNonaktif($request)
    {
        User::create([
            'name' => $request->name,
            'nomor_rumah' => $request->nomor_rumah,
            'role' => 'warga',
            'status_aktif' => 0,
            'username' => 'histori_' . time() . rand(10,99),
            'password' => Hash::make(Str::random(10))
        ]);
    }
}