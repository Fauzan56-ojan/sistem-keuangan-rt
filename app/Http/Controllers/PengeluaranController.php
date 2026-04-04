<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    public function index()
    {
        $data = Pengeluaran::latest()->get();
        return view('pengeluaran.index', compact('data'));
    }

    public function create()
    {
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id()
        ]);

        return redirect('/pengeluaran');
    }

    public function edit($id)
    {
        $data = Pengeluaran::findOrFail($id);
        return view('pengeluaran.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Pengeluaran::findOrFail($id);

        $data->update([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/pengeluaran');
    }

    public function destroy($id)
    {
        $data = Pengeluaran::findOrFail($id);
        $data->delete();

        return redirect('/pengeluaran');
    }
}