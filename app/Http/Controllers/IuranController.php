<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Iuran;
use App\Models\User;
use App\Models\Pembayaran;
use App\Services\IuranService;

use Illuminate\Pagination\LengthAwarePaginator;

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

        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        $summary = $service->getSummaryBulanIni();

        return view('iuran.warga-list', compact('users') + $summary);
    }
    
    public function warga($id, IuranService $service)
    {
        if (auth()->user()->role === 'warga' && auth()->id() != $id) {
            abort(403);
        }

        $tahun = request('tahun', date('Y'));
        $warga = User::where('status_aktif', 1)
            ->findOrFail($id);

        $data = $service->getDataWarga($id, $tahun);

        $iuran = $data['iuran'];
        $tahunList = $data['tahunList'];
        $lastPaid = $data['lastPaid'];

        return view('iuran.warga', compact('iuran', 'tahun', 'tahunList', 'lastPaid', 'warga'));
    }

    public function belumBayar()
    {
        $data = Iuran::with('user')
            ->whereHas('user', function ($q) {
                $q->where('status_aktif', 1);
            })
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

    public function tunggakan(IuranService $service)
    {
        $allData = collect($service->getTunggakan())->map(function ($item) use ($service) {
            $item['detail'] = $service->getTunggakanDetail($item['user_id']);
            return $item;
        });

        $page = request('page', 1);
        $perPage = 10;
        $currentPageData = $allData->slice(($page - 1) * $perPage, $perPage)->values();

        $data = new LengthAwarePaginator(
            $currentPageData,
            $allData->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('tunggakan.index', compact('data'));
    } 

    public function tunggakanDetail($id, IuranService $service)
    {
        if (auth()->user()->role === 'warga' && auth()->id() != $id) {
            abort(403);
        }

        $warga = User::where('status_aktif', 1)
            ->findOrFail($id);
        $iuran = $service->getTunggakanDetail($id);

        return view('tunggakan.detail', compact('iuran', 'warga'));
    }

    public function histori()
    {
        $data = IuranService::getHistoriData();

        return view('settings.histori.index', [
            'aktif' => $data['aktif'],
            'nonaktif' => $data['nonaktif']
        ]);
    }

    public function historiDetail($id)
    {
        $data = IuranService::getHistoriDetail($id);

        return view('settings.histori.detail', [
            'warga' => $data['warga'],
            'iuran' => $data['iuran'],
            'tahun' => $data['tahun'],
            'tahunList' => $data['tahunList'],
            'lastPaid' => $data['lastPaid']
        ]);
    }

    public function storeHistori(Request $request, $id)
    {
        if (!$request->bulan || !is_array($request->bulan)) {
            return back()->with('error', 'Pilih minimal satu bulan');
        }
        $berhasil = IuranService::storeHistori($request, $id);

            if ($berhasil > 0) {
            return back()->with('success', 'Histori berhasil ditambahkan');
        }

        return back()->with('error', 'Tidak ada data yang berhasil disimpan');
    }

    public function storeNonaktif(Request $request)
    {
        IuranService::storeNonaktif($request);

        return back()->with('success', 'Warga nonaktif berhasil ditambahkan');
    }

    public function updateNonaktif(Request $request, $id)
    {
        $user = User::where('role', 'warga')->where('status_aktif', 0)->findOrFail($id);

        $user->update([
            'name' => $request->name,
            'nomor_rumah' => $request->nomor_rumah,
        ]);

        return back()->with('success', 'Data warga nonaktif berhasil diperbarui');
    }

}
