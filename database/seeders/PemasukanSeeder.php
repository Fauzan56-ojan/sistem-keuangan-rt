<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemasukanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();

        $pemasukan = [];

        // ===== 2025 =====
        // Januari
        $pemasukan[] = ['2025-01-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-01-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-01-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-01-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-01-25', 50000,  'Sumbangan tahun baru dari warga'];

        // Februari
        $pemasukan[] = ['2025-02-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-02-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-02-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-02-18', 80000,  'Sumbangan kas RT bulanan'];

        // Maret
        $pemasukan[] = ['2025-03-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-03-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-03-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-03-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-03-22', 150000, 'Sumbangan perbaikan pagar aula'];

        // April
        $pemasukan[] = ['2025-04-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-04-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-04-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-04-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-04-22', 75000,  'Donasi dari warga perantau'];

        // Mei
        $pemasukan[] = ['2025-05-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-05-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-05-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-05-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-05-25', 200000, 'Sumbangan dari donatur lingkungan'];

        // Juni
        $pemasukan[] = ['2025-06-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-06-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-06-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-06-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-06-22', 120000, 'Sumbangan perayaan Idul Adha'];

        // Juli
        $pemasukan[] = ['2025-07-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-07-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-07-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-07-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-07-25', 60000,  'Sumbangan dari pedagang kaki lima'];

        // Agustus
        $pemasukan[] = ['2025-08-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-08-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-08-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-08-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-08-20', 300000, 'Sumbangan perayaan 17 Agustus'];

        // September
        $pemasukan[] = ['2025-09-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-09-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-09-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-09-18', 80000,  'Sumbangan kas RT bulanan'];

        // Oktober
        $pemasukan[] = ['2025-10-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-10-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-10-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-10-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-10-22', 100000, 'Sumbangan perbaikan Mushola'];

        // November
        $pemasukan[] = ['2025-11-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-11-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-11-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-11-18', 80000,  'Sumbangan kas RT bulanan'];

        // Desember
        $pemasukan[] = ['2025-12-05', 45000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2025-12-07', 35000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2025-12-10', 30000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2025-12-18', 80000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2025-12-22', 150000, 'Sumbangan akhir tahun'];

        // ===== 2026 =====
        // Januari
        $pemasukan[] = ['2026-01-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-01-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-01-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-01-18', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-01-25', 100000, 'Sumbangan tahun baru'];

        // Februari
        $pemasukan[] = ['2026-02-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-02-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-02-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-02-18', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-02-22', 85000,  'Sumbangan dari warga baru'];

        // Maret
        $pemasukan[] = ['2026-03-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-03-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-03-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-03-18', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-03-22', 200000, 'Sumbangan dari donatur'];

        // April
        $pemasukan[] = ['2026-04-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-04-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-04-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-04-15', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-04-20', 125000, 'Sumbangan perbaikan saluran air'];

        // Mei
        $pemasukan[] = ['2026-05-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-05-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-05-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-05-15', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-05-22', 70000,  'Donasi dari pedagang pasar'];

        // Juni
        $pemasukan[] = ['2026-06-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-06-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-06-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-06-15', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-06-20', 150000, 'Sumbangan perbaikan pagar aula'];

        // Juli
        $pemasukan[] = ['2026-07-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-07-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-07-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-07-15', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-07-20', 90000,  'Sumbangan dari donatur lingkungan'];

        // Agustus
        $pemasukan[] = ['2026-08-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-08-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-08-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-08-15', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-08-18', 350000, 'Sumbangan perayaan 17 Agustus'];

        // September
        $pemasukan[] = ['2026-09-05', 50000,  'Sumbangan kebersihan warga blok ET'];
        $pemasukan[] = ['2026-09-07', 40000,  'Sumbangan kebersihan warga blok GT'];
        $pemasukan[] = ['2026-09-10', 35000,  'Sumbangan kebersihan warga blok H'];
        $pemasukan[] = ['2026-09-15', 90000,  'Sumbangan kas RT bulanan'];
        $pemasukan[] = ['2026-09-20', 100000, 'Sumbangan perbaikan lampu jalan'];

        foreach ($pemasukan as [$tanggal, $nominal, $keterangan]) {
            DB::table('pemasukan')->insert([
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
