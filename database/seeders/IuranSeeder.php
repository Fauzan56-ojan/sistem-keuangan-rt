<?php

namespace Database\Seeders;

use App\Models\Iuran;
use App\Models\User;
use Illuminate\Database\Seeder;

class IuranSeeder extends Seeder
{
    public function run(): void
    {
        $nominal2025 = 20000;
        $nominal2026 = 30000;

        $semuaWarga = User::where('role', 'warga')->get();

        // Tunggakan 2026: email => lunas s/d bulan keberapa
        $tunggakan2026 = [
            // Tunggakan 1 bulan (Sep belum bayar)
            'budi@example.com' => 8,
            'andi@example.com' => 8,
            'dani@example.com' => 8,
            'ekow@example.com' => 8,
            'fajar@example.com' => 8,
            'gilang@example.com' => 8,

            // Tunggakan 2 bulan (Agst-Sep belum bayar)
            'hadi@example.com' => 7,
            'indra@example.com' => 7,
            'joko@example.com' => 7,
            'kurniawan@example.com' => 7,
        ];

        foreach ($semuaWarga as $user) {
            if ($user->status_aktif) {
                // === 2025: Jan-Des, semua lunas ===
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    Iuran::create([
                        'user_id' => $user->id,
                        'periode_bulan' => $bulan,
                        'periode_tahun' => 2025,
                        'nominal' => $nominal2025,
                        'status' => 'paid',
                    ]);
                }

                // === 2026: Jan-Des ===
                $lunasSampai = $tunggakan2026[$user->email] ?? 9;

                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    if ($bulan <= $lunasSampai) {
                        $status = 'paid';
                    } elseif ($bulan <= 9) {
                        // Agst/Sep: belum bayar (tunggakan)
                        $status = 'pending';
                    } else {
                        // Okt-Des: semua belum bayar
                        $status = 'pending';
                    }

                    Iuran::create([
                        'user_id' => $user->id,
                        'periode_bulan' => $bulan,
                        'periode_tahun' => 2026,
                        'nominal' => $nominal2026,
                        'status' => $status,
                    ]);
                }
            } else {
                // Warga nonaktif: 2025 lunas Jan-Jun, Jul-Dec pending
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    Iuran::create([
                        'user_id' => $user->id,
                        'periode_bulan' => $bulan,
                        'periode_tahun' => 2025,
                        'nominal' => $nominal2025,
                        'status' => $bulan <= 6 ? 'paid' : 'pending',
                    ]);
                }
            }
        }
    }
}
