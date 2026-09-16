<?php

namespace Database\Seeders;

use App\Models\Iuran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $metode = ['Tunai', 'QRIS', 'GoPay', 'ShopeePay', 'Dana', 'Transfer BCA', 'Transfer Mandiri'];

        $paidIurans = Iuran::where('status', 'paid')->get();

        foreach ($paidIurans as $iuran) {
            // Tanggal bayar antara tanggal 1-15 di bulan periode
            $hari = rand(1, 15);
            $tanggalBayar = sprintf(
                '%04d-%02d-%02d',
                $iuran->periode_tahun,
                $iuran->periode_bulan,
                $hari
            );

            // 2025 kebanyakan tunai (80%), 2026 bervariasi
            if ($iuran->periode_tahun === 2025) {
                $metodeBayar = rand(1, 100) <= 80 ? 'Tunai' : $metode[array_rand($metode)];
            } else {
                $metodeBayar = $metode[array_rand($metode)];
            }

            $orderId = $metodeBayar !== 'Tunai' ? 'ORD-' . strtoupper(uniqid()) : null;

            DB::table('pembayaran')->insert([
                'iuran_id'       => $iuran->id,
                'user_id'        => $iuran->user_id,
                'metode'         => $metodeBayar,
                'order_id'       => $orderId,
                'kode_transaksi' => 'TRX-' . str_pad($iuran->id, 6, '0', STR_PAD_LEFT),
                'amount'         => $iuran->nominal,
                'snap_token'     => null,
                'paid_at'        => $tanggalBayar,
                'status'         => 'success',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
