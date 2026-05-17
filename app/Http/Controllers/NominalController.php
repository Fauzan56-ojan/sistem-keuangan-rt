<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NominalController extends Controller
{
    public function index()
    {
        $data = \DB::table('setnominal')
            ->leftJoin('users', 'users.id', '=', 'setnominal.created_by')
            ->select('setnominal.*', 'users.name as user_name')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('settings.nominal', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0'
        ]);

        $tahun = date('Y');
        $bulan = date('n');

        $cek = \DB::table('setnominal')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        if ($cek) {
            \DB::table('setnominal')
                ->where('id', $cek->id)
                ->update([
                    'nominal' => $request->nominal,
                    'updated_at' => now()
                ]);
        } else {
            \DB::table('setnominal')->insert([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'nominal' => $request->nominal,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        \DB::table('iuran')
            ->where('periode_tahun', $tahun)
            ->where('periode_bulan', '>=', $bulan)
            ->where('status', 'pending')
            ->update([
                'nominal' => $request->nominal
            ]);

        return redirect()->route('settings.nominal')->with('success', 'Nominal iuran berhasil diperbarui');
    }
}
