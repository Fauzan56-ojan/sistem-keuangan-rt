<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();

        $pengeluaran = [];

        // Pengeluaran rutin per bulan: 8-10 item
        $keteranganRutin = [
            ['Bayar kebersihan lingkungan',  150000, 250000],
            ['Bayar keamanan satpam',        250000, 350000],
            ['Bayar honor satpam minggu 1',  75000,  100000],
            ['Bayar honor satpam minggu 3',  75000,  100000],
            ['Listrik lampu jalan',          150000, 250000],
            ['Listrik aula RT',              80000,  120000],
            ['Operasional RT',               100000, 200000],
            ['Perawatan aula & fasilitas',   80000,  150000],
            ['Sampah dan pengangkutan',      50000,  100000],
            ['Beli alat tulis kantor RT',    25000,  75000],
        ];

        for ($tahun = 2025; $tahun <= 2026; $tahun++) {
            $maxBulan = $tahun === 2025 ? 12 : 9;
            for ($bulan = 1; $bulan <= $maxBulan; $bulan++) {
                $tgl = sprintf('%04d-%02d', $tahun, $bulan);

                foreach ($keteranganRutin as [$ket, $min, $max]) {
                    $nominal = rand($min, $max);
                    // Bulatkan ke ribuan
                    $nominal = round($nominal / 1000) * 1000;
                    $pengeluaran[] = ["{$tgl}-" . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT), $nominal, $ket];
                }
            }
        }

        // Pengeluaran berkala 2025
        $berkala2025 = [
            ['2025-02-15', 150000, 'Beli alat kebersihan (sapu, pel, dll)'],
            ['2025-03-10', 100000, 'Service pompa air'],
            ['2025-04-10', 300000, 'Perbaikan saluran air'],
            ['2025-05-20', 120000, 'Beli seragam satpam'],
            ['2025-06-20', 100000, 'Beli tong sampah'],
            ['2025-07-10', 250000, 'Service lampu jalan'],
            ['2025-08-01', 500000, 'Dekorasi 17 Agustus'],
            ['2025-09-15', 150000, 'Beli alat kebersihan'],
            ['2025-10-10', 200000, 'Perbaikan pagar aula'],
            ['2025-11-10', 350000, 'Perbaikan jalan rusak'],
            ['2025-12-15', 180000, 'Beli lampu hias aula'],
        ];

        // Pengeluaran berkala 2026
        $berkala2026 = [
            ['2026-01-15', 150000, 'Beli alat kebersihan'],
            ['2026-02-10', 120000, 'Service pompa air'],
            ['2026-03-10', 200000, 'Service lampu jalan'],
            ['2026-04-15', 180000, 'Perawatan pagar aula'],
            ['2026-05-20', 300000, 'Perbaikan saluran air'],
            ['2026-06-15', 120000, 'Beli alat kebersihan'],
            ['2026-07-10', 250000, 'Service lampu jalan'],
            ['2026-08-01', 450000, 'Dekorasi 17 Agustus'],
            ['2026-09-10', 175000, 'Beli alat kebersihan'],
        ];

        $pengeluaran = array_merge($pengeluaran, $berkala2025, $berkala2026);

        foreach ($pengeluaran as [$tanggal, $nominal, $keterangan]) {
            DB::table('pengeluaran')->insert([
                'tanggal'     => $tanggal,
                'nominal'     => $nominal,
                'keterangan'  => $keterangan,
                'bukti_file'  => null,
                'created_by'  => $admin->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
