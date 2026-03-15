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
    try {

        $iuran = Iuran::with('user')->findOrFail($id);

        PembayaranService::checkUrutan($iuran);

        return view('pembayaran.checkout', compact('iuran','metode'));

    } catch (\Exception $e) {

        return back()->with('error',$e->getMessage());

    }
}


}
