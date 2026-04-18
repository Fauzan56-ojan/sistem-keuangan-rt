<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pemasukan;


class PemasukanController extends Controller
{
    public function index()
    {
        $data = Pemasukan::latest()->get();
        return view('pemasukan.index', compact('data'));
    }

    public function create()
    {
        $this->authorizeAdminBendahara();
        return view('pemasukan.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdminBendahara();
        $bukti = null;
        if ($request->hasFile('bukti_file')) {
            $bukti = $request->file('bukti_file')->store('bukti', 'public');
        }
        Pemasukan::create([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id(),
            'bukti_file' => $bukti
        ]);

        return redirect('/pemasukan');
    }

    public function edit($id)
    {
        $this->authorizeAdminBendahara();
        $data = Pemasukan::findOrFail($id);
        return view('pemasukan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdminBendahara();
        $data = Pemasukan::findOrFail($id);
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

        return redirect('/pemasukan');
    }

    public function destroy($id)
    {
        $this->authorizeAdminBendahara();
        $data = Pemasukan::findOrFail($id);
        $data->delete(); 

        return redirect('/pemasukan');
    }

}