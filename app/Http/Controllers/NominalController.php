<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NominalController extends Controller
{
    public function index()
    {
        $data = \DB::table('setnominal')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('nominal.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0'
        ]);

        $tahun = date('Y');
        $bulan = date('n');

        \DB::table('setnominal')->insert([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'nominal' => $request->nominal,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('iuran')
            ->where('periode_tahun', $tahun)
            ->where('periode_bulan', '>=', $bulan)
            ->where('status', 'pending')
            ->update([
                'nominal' => $request->nominal
            ]);

        return redirect()->route('nominal.index')
            ->with('success', 'Nominal berhasil ditambahkan & iuran diperbarui');
    }
}
