<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\User;

class IuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            ->select('id', 'name', 'nomor_rumah')
            ->orderBy('name')
            ->get();

        return view('iuran.warga_list', compact('users'));
    }
    
    public function warga($id)
    {
        $tahun = date('Y');

        $iuran = Iuran::where('user_id', $id)
            ->where('periode_tahun', $tahun)
            ->orderBy('periode_bulan')
            ->get();

        return view('iuran.warga', compact('iuran','tahun'));
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
