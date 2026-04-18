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
        $this->authorizeAdminBendahara();
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdminBendahara();
        $bukti = null;
        if ($request->hasFile('bukti_file')) {
            $bukti = $request->file('bukti_file')->store('bukti', 'public');
        }
        Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id(),
            'bukti_file' => $bukti
        ]);

        return redirect('/pengeluaran');
    }

    public function edit($id)
    {
        $this->authorizeAdminBendahara();
        $data = Pengeluaran::findOrFail($id);
        return view('pengeluaran.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdminBendahara();
        $data = Pengeluaran::findOrFail($id);
        $bukti = $data->bukti_file; 
        if ($request->hasFile('bukti_file')) {
            $bukti = $request->file('bukti_file')->store('bukti', 'public');
        }

        $data->update([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'bukti_file' => $bukti
        ]);

        return redirect('/pengeluaran');
    }

    public function destroy($id)
    {
        $this->authorizeAdminBendahara();
        $data = Pengeluaran::findOrFail($id);
        $data->delete();

        return redirect('/pengeluaran');
    }
}