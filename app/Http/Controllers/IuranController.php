<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Iuran;
use App\Models\User;

class IuranController extends Controller
{
    public function index()
    {
        $iuran = Iuran::with('user')
            ->orderBy('periode_tahun','desc')
            ->orderBy('periode_bulan')
            ->get();

        return view('iuran.index', compact('iuran'));
    }

    public function wargaList()
    {
        $users = User::where('status_aktif', 1)
            ->where('role', 'warga')
            ->select('id', 'name', 'nomor_rumah')
            ->orderBy('name')
            ->get();

        return view('iuran.warga-list', compact('users'));
    }
    
    public function warga($id)
    {
        $tahun = request('tahun', date('Y'));

        $iuran = Iuran::where('user_id', $id)
            ->where('periode_tahun', $tahun)
            ->orderBy('periode_bulan')
            ->get();
        
        $tahunList = Iuran::where('user_id', $id)
            ->select('periode_tahun')
            ->distinct()
            ->orderByDesc('periode_tahun')
            ->pluck('periode_tahun');

        return view('iuran.warga', compact('iuran','tahun', 'tahunList'));
    }

    public function generate(Request $request)
    {
        $tahun = $request->tahun;

        $cek = DB::table('iuran')
            ->where('periode_tahun', $tahun)
            ->exists();

        if ($cek) {
            return back()->with('error', 'Iuran tahun ini sudah digenerate');
        }

        $cekNominal = DB::table('setnominal')
            ->where('tahun', $tahun)
            ->exists();

        if (!$cekNominal) {

            $nominalTerakhir = DB::table('setnominal')
                ->orderBy('created_at', 'desc')
                ->value('nominal');

            if (!$nominalTerakhir) {
                return back()->with('error', 'Nominal sebelumnya belum ada');
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
                    return back()->with('error', "Nominal belum diset sampai bulan $bulan");
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

        return back()->with('success', 'Iuran berhasil digenerate');
    }

    public function migrasi(Request $request)
    {
        $tahun = $request->tahun;
        $bulanAkhir = $request->bulan;

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
                    return back()->with('error', "Nominal belum diset sampai bulan $i");
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

        return back()->with('success', 'Migrasi berhasil');
    }

    public function tunggakan()
    {
        $bulanSekarang = date('m');
        $tahunSekarang = date('Y');

        $iuran = Iuran::with('user')
            ->where('periode_tahun', $tahunSekarang)
            ->where('periode_bulan', '<', $bulanSekarang)
            ->where('status', 'pending')
            ->get();

        $data = $iuran->groupBy('user_id')->map(function ($items) {
            return [
                'nama' => $items->first()->user->name,
                'jumlah_bulan' => $items->count(),
                'total' => $items->sum('nominal'),
                'user_id' => $items->first()->user_id
            ];
        })->values(); 

        return view('tunggakan.index', compact('data'));
    }

    public function tunggakanDetail($id)
    {
        $nowYear = now()->year;
        $nowMonth = now()->month;

        $iuran = Iuran::where('user_id', $id)
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

        return view('tunggakan.detail', compact('iuran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
