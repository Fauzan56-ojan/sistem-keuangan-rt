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
        try {

            $iuran = Iuran::with('user')->findOrFail($id);

            PembayaranService::checkUrutan($iuran);

            return view('pembayaran.checkout', compact('iuran','metode'));

        } catch (\Exception $e) {

            return back()->with('error',$e->getMessage());

        }
    }

    public function riwayat()
    {
        $tahun = request('tahun', now()->year);
        if (auth()->user()->role == 'warga') {
            $data = Pembayaran::where('user_id', auth()->id())
                ->where(function ($q) use ($tahun) {
                    $q->whereYear('paid_at', $tahun)
                    ->orWhere(function ($q2) use ($tahun) {
                        $q2->whereNull('paid_at')
                            ->whereYear('created_at', $tahun);
                    });
                })
                ->orderByRaw('COALESCE(paid_at, created_at) DESC')
                ->get();
        } else { 
            $query = Pembayaran::with('user');

            if (request('search')) {
                $search = request('search');

                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('nomor_rumah', 'like', "%$search%");
                });
        }
        $data = $query
            ->where(function ($q) use ($tahun) {
                $q->whereYear('paid_at', $tahun)
                ->orWhere(function ($q2) use ($tahun) {
                    $q2->whereNull('paid_at')
                        ->whereYear('created_at', $tahun);
                });
            })
            ->orderByRaw('COALESCE(paid_at, created_at) DESC')
            ->get();
        }
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
