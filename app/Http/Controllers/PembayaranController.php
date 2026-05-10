<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Services\PembayaranService;
use Midtrans\Config;
use Midtrans\Snap;


class PembayaranController extends Controller
{
    public function tunai($id)
    {
        PembayaranService::createTunaiPayment($id);

        return back();
    }

    // public function online($id)
    // {
    //     $snapToken = PembayaranService::createOnlinePayment($id);

    //     return view('pembayaran.snap', compact('snapToken'));
    // }

    public function getSnapToken($id)
    {
        $user = auth()->user();
        if ($user->role === 'ketua_rt') {
            abort(403);
        }

        $iuran = Iuran::findOrFail($id);

        if ($user->role === 'warga' && $iuran->user_id != $user->id) {
            abort(403);
        }
        $snapToken = PembayaranService::createOnlinePayment($id);

        return response()->json([
            'token' => $snapToken
        ]);
    }

    public function webhook()
    {
        return PembayaranService::handleWebhook();
    }

    public function checkout($id,$metode)
    {
        if ($metode === 'tunai' && !in_array(auth()->user()->role, ['admin','bendahara'])) {
            abort(403);
        }

        if ($metode === 'online' && auth()->user()->role === 'ketua_rt') {
            abort(403);
        }

        $iuran = Iuran::with('user')->findOrFail($id);

        if ($iuran->user->status_aktif != 1) {
            abort(403, 'Warga sudah tidak aktif');
        }
        try {

            PembayaranService::checkUrutan($iuran);

            return view('pembayaran.checkout', compact('iuran','metode'));

        } catch (\Exception $e) {

            return back()->with('error',$e->getMessage());

        }
    }

    public function riwayat()
    {
        $tahun = request('tahun', now()->year);
        $bulan = request('bulan');
        $search = request('search');
        $sort = request('sort');

        if (auth()->user()->role == 'warga') {

            $query = Pembayaran::where('user_id', auth()->id());

        } else {

            $query = Pembayaran::with('user');

            if ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('nomor_rumah', 'like', "%$search%");
                });
            }
        }

        if ($tahun && $tahun != 'all') {
            $query->where(function ($q) use ($tahun) {
                $q->whereYear('paid_at', $tahun)
                ->orWhere(function ($q2) use ($tahun) {
                    $q2->whereNull('paid_at')
                        ->whereYear('created_at', $tahun);
                });
            });
        }

        if ($bulan && $bulan != 'all') {
            $query->where(function ($q) use ($bulan) {
                $q->whereMonth('paid_at', $bulan)
                ->orWhere(function ($q2) use ($bulan) {
                    $q2->whereNull('paid_at')
                        ->whereMonth('created_at', $bulan);
                });
            });
        }

        if ($sort == 'tanggal_asc') {
            $query->orderByRaw('COALESCE(paid_at, created_at) ASC');
        } else {
            $query->orderByRaw('COALESCE(paid_at, created_at) DESC');
        }

        $data = $query->get();

        $tahunList = Pembayaran::selectRaw('YEAR(paid_at) as tahun')
            ->whereNotNull('paid_at')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('pembayaran.riwayat', compact('data', 'tahunList'));
    }

    public function batal($id)
    {
        $data = Pembayaran::findOrFail($id);

        if ($data->user_id != auth()->id()) {
            abort(403);
        }

        if ($data->status == 'pending') {
            $data->delete();
        }

        return back();
    }

}
