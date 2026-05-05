<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Iuran;
use App\Models\User;
use App\Models\Pembayaran;
use App\Services\IuranService;

class IuranController extends Controller
{
    public function index() //tidak kepakai
    {
        return view('iuran.index', compact('iuran'));
    }

    public function wargaList(IuranService $service)
    {
        $query = User::where('status_aktif', 1)
            ->where('role', 'warga')
            ->select('id', 'name', 'nomor_rumah');

        if (request('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('nomor_rumah', 'like', "%$search%");
            });
        }

        $users = $query->orderBy('name')->get();
        $summary = $service->getSummaryBulanIni();

        return view('iuran.warga-list', compact('users') + $summary);
    }
    
    public function warga($id, IuranService $service)
    {
        if (auth()->user()->role === 'warga' && auth()->id() != $id) {
            abort(403);
        }

        $tahun = request('tahun', date('Y'));
        $warga = User::findOrFail($id);

        $data = $service->getDataWarga($id, $tahun);

        $iuran = $data['iuran'];
        $tahunList = $data['tahunList'];
        $lastPaid = $data['lastPaid'];

        return view('iuran.warga', compact('iuran', 'tahun', 'tahunList', 'lastPaid', 'warga'));
    }

    public function belumBayar()
    {
        $data = Iuran::with('user')
            ->where('periode_bulan', now()->month)
            ->where('periode_tahun', now()->year)
            ->where('status', 'pending')
            ->get();

        return view('iuran.belum-bayar', compact('data'));
    }

    public function generate(Request $request, IuranService $service)
    {
        $result = $service->generate($request->tahun);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', $result['success']);
    }

    public function migrasi(Request $request, IuranService $service)
    {
        $result = $service->migrasi($request->tahun, $request->bulan);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', $result['success']);
    }

    public function tunggakan(IuranService $service)
    {
        $data = collect($service->getTunggakan())->map(function ($item) use ($service) {
            $item['detail'] = $service->getTunggakanDetail($item['user_id']);
            return $item;
        });

        return view('tunggakan.index', compact('data'));
    } 

    public function tunggakanDetail($id, IuranService $service)
    {
        if (auth()->user()->role === 'warga' && auth()->id() != $id) {
            abort(403);
        }

        $warga = User::findOrFail($id);
        $iuran = $service->getTunggakanDetail($id);

        return view('tunggakan.detail', compact('iuran', 'warga'));
    }

}
