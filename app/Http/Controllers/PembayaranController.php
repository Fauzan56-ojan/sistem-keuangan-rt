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
        if (auth()->user()->role == 'warga') {
            $data = Pembayaran::where('user_id', auth()->id())
                ->latest()
                ->get();
        } else {
            $data = Pembayaran::latest()->get();
        }

        return view('pembayaran.riwayat', compact('data'));
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
