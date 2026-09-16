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
        $sort = request('sort', 'tanggal_desc');

        $sortMap = [
            'tanggal_desc' => ['tanggal', 'desc'],
            'tanggal_asc' => ['tanggal', 'asc'],
            'nominal_desc' => ['nominal', 'desc'],
            'nominal_asc' => ['nominal', 'asc'],
        ];

        [$sortBy, $order] = $sortMap[$sort] ?? ['tanggal', 'desc'];

        $query = Pemasukan::query();

        if ($bulan !== 'all') {
            $query->whereMonth('tanggal', $bulan);
        }

        if ($tahun !== 'all') {
            $query->whereYear('tanggal', $tahun);
        }

        if (request('search')) {
            $query->where('keterangan', 'like', '%' . request('search') . '%');
        }

        $data = $query->orderBy($sortBy, $order)->paginate(10)->withQueryString();

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

        $request->validate([
            'tanggal'     => 'required|date',
            'nominal'     => 'required|numeric|min:1',
            'keterangan'  => 'required|string|max:255',
            'bukti_file'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $bukti = null;
        if ($request->hasFile('bukti_file')) {
            $bukti = $request->file('bukti_file')->store('bukti', 'public');
        }

        Pemasukan::create([
            'tanggal'     => $request->tanggal,
            'nominal'     => $request->nominal,
            'keterangan'  => $request->keterangan,
            'created_by'  => auth()->id(),
            'bukti_file'  => $bukti,
        ]);

        return redirect('/pemasukan')->with('success', 'Data berhasil ditambahkan');
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

        $request->validate([
            'tanggal'     => 'required|date',
            'nominal'     => 'required|numeric|min:1',
            'keterangan'  => 'required|string|max:255',
            'bukti_file'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = Pemasukan::findOrFail($id);
        $bukti = $data->bukti_file;

        if ($request->hasFile('bukti_file')) {
            $bukti = $request->file('bukti_file')->store('bukti', 'public');
        }

        $data->update([
            'tanggal'     => $request->tanggal,
            'nominal'     => $request->nominal,
            'keterangan'  => $request->keterangan,
            'bukti_file'  => $bukti,
        ]);

        return redirect('/pemasukan')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->authorizeAdminBendahara();
        $data = Pemasukan::findOrFail($id);
        $data->delete(); 

        return redirect('/pemasukan')->with('success', 'Data berhasil dihapus');
    }

}