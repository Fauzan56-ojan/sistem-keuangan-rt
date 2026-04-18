<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Pemasukan;


class PemasukanController extends Controller
{
    public function index()
    {
        $bulan = request('bulan', now()->month);
        $tahun = request('tahun', now()->year);
        $allowedSort = ['tanggal', 'nominal'];

        $sortBy = in_array(request('sort_by'), $allowedSort)
            ? request('sort_by')
            : 'tanggal';

        $order = request('order') === 'asc' ? 'asc' : 'desc';

        $data = Pemasukan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy($sortBy, $order)
            ->get();

        $tahunList = Pemasukan::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('pemasukan.index', compact('data', 'tahunList'));
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