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

        // Tunggakan 2026: username => lunas s/d bulan keberapa
        // Max 2 bulan tunggakan, max Rp60.000 per orang
        $tunggakan2026 = [
            // Tunggakan 1 bulan (Sep belum bayar) — Rp30.000
            'budi'     => 8,
            'andi'     => 8,
            'dani'     => 8,
            'ekow'     => 8,
            'fajar'    => 8,
            'gilang'   => 8,

            // Tunggakan 2 bulan (Aug-Sep belum bayar) — Rp60.000
            'hadi'     => 7,
            'indra'    => 7,
            'joko'     => 7,
            'kurniawan'=> 7,
        ];

        foreach ($semuaWarga as $user) {
            if ($user->status_aktif) {
                // === 2025: semua lunas ===
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    Iuran::create([
                        'user_id'       => $user->id,
                        'periode_bulan' => $bulan,
                        'periode_tahun' => 2025,
                        'nominal'       => $nominal2025,
                        'status'        => 'paid',
                    ]);
                }

                // === 2026 Jan-Sep ===
                $lunasSampai = $tunggakan2026[$user->username] ?? 9;

                for ($bulan = 1; $bulan <= 9; $bulan++) {
                    Iuran::create([
                        'user_id'       => $user->id,
                        'periode_bulan' => $bulan,
                        'periode_tahun' => 2026,
                        'nominal'       => $nominal2026,
                        'status'        => $bulan <= $lunasSampai ? 'paid' : 'pending',
                    ]);
                }
            } else {
                // Warga nonaktif: 2025 lunas Jan-Jun, Jul-Dec pending
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    Iuran::create([
                        'user_id'       => $user->id,
                        'periode_bulan' => $bulan,
                        'periode_tahun' => 2025,
                        'nominal'       => $nominal2025,
                        'status'        => $bulan <= 6 ? 'paid' : 'pending',
                    ]);
                }
            }
        }
    }
}
