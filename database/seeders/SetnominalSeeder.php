<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetnominalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('setnominal')->insert([
            'tahun' => 2025,
            'bulan' => 1,
            'nominal' => 20000,
            'created_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('setnominal')->insert([
            'tahun' => 2026,
            'bulan' => 1,
            'nominal' => 30000,
            'created_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
