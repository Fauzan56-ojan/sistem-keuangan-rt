<?php

namespace App\Services;

use App\Models\Iuran;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PembayaranService
{
    public static function createTunaiPayment($id)
    {
        $iuran = Iuran::findOrFail($id);

        if($iuran->status == 'paid'){
            return null;
        }

        self::checkUrutan($iuran);

        Pembayaran::where('iuran_id',$id)
        ->where('status','pending')
        ->delete();

        $pembayaran = Pembayaran::create([
            'user_id' => $iuran->user_id,
            'iuran_id' => $iuran->id,
            'amount' => $iuran->nominal,
            'metode' => 'tunai',
            'status' => 'success',
            'paid_at' => now()
        ]);

        $iuran->update([
            'status' => 'paid'
        ]);

        return $pembayaran;
    }

    public static function checkUrutan($iuran)
{
    $bulanSebelumnya = $iuran->periode_bulan - 1;
    $tahun = $iuran->periode_tahun;

    if($bulanSebelumnya == 0){
        $bulanSebelumnya = 12;
        $tahun = $tahun - 1;
    }

    $previous = Iuran::where('user_id',$iuran->user_id)
        ->where('periode_tahun',$tahun)
        ->where('periode_bulan',$bulanSebelumnya)
        ->first();

    if($previous && $previous->status != 'paid'){
        throw new \Exception('Harus bayar bulan sebelumnya dulu');
    }

    return true;
}

    public static function createOnlinePayment($id)
    {
        $iuran = Iuran::findOrFail($id);

        self::checkUrutan($iuran);

        if ($iuran->status == 'paid') {
            return null;
        }


        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // cek pending
        $existing = Pembayaran::where('iuran_id', $iuran->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {

    // kalau sudah ada snap_token pakai yang lama
    if ($existing->snap_token) {
        return $existing->snap_token;
    }

    // kalau token belum ada, buat token baru
    $params = [
        'transaction_details' => [
            'order_id' => $existing->order_id,
            'gross_amount' => $existing->amount
        ],
        'customer_details' => [
            'first_name' => $iuran->user->name
        ]
    ];

    $snapToken = Snap::getSnapToken($params);

    $existing->update([
        'snap_token' => $snapToken
    ]);

    return $snapToken;
}

        // buat order baru
        $order_id = 'KASRT'.$iuran->id.'-'.time();

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $iuran->nominal
            ],
            'customer_details' => [
                'first_name' => $iuran->user->name
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        // simpan pembayaran
        $pembayaran = Pembayaran::create([
            'user_id' => $iuran->user_id,
            'iuran_id' => $iuran->id,
            'amount' => $iuran->nominal,
            'metode' => 'online',
            'order_id' => $order_id,
            'snap_token' => $snapToken,
            'status' => 'pending'
        ]);

        return $snapToken;
    }

    public static function handleWebhook()
    {
        try {

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            $notification = new Notification();

            $order_id = $notification->order_id;
            $status = $notification->transaction_status;

            $pembayaran = Pembayaran::where('order_id', $order_id)->first();

            if (!$pembayaran) {
                return response()->json(['message' => 'pembayaran tidak ditemukan']);
            }

            if ($status == 'settlement' || $status == 'capture') {

                $pembayaran->update([
                    'status' => 'success',
                    'paid_at' => now()
                ]);

                $iuran = Iuran::find($pembayaran->iuran_id);

                $iuran->update([
                    'status' => 'paid'
                ]);
            }

            if ($status == 'expire') {
                $pembayaran->update([
                    'status' => 'failed'
                ]);
            }

            return response()->json(['message' => 'ok']);

        } catch (\Exception $e) {

            \Log::error($e->getMessage());

            return response()->json(['message' => 'error'], 200);
        }
    }
}