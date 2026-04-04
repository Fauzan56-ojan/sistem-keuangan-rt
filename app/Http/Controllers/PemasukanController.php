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
        return view('pemasukan.create');
    }

    public function store(Request $request)
    {
        Pemasukan::create([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'created_by' => auth()->id()
        ]);

        return redirect('/pemasukan');
    }

        public function edit($id)
    {
        $data = Pemasukan::findOrFail($id);
        return view('pemasukan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Pemasukan::findOrFail($id);

        $data->update([
            'tanggal' => $request->tanggal,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/pemasukan');
    }

    public function destroy($id)
    {
        $data = Pemasukan::findOrFail($id);
        $data->delete(); // sementara hard delete

        return redirect('/pemasukan');
    }

}