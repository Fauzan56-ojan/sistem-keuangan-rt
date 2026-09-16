<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('123');

        // Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'nomor_rumah' => 'Kantor RT',
            'telp' => '081200000001',
            'role' => 'admin',
            'status_aktif' => true,
            'password' => $password,
            'password_changed' => false,
        ]);

        // Bendahara
        User::create([
            'name' => 'Siti Aminah',
            'username' => 'bendahara',
            'nomor_rumah' => 'Kantor RT',
            'telp' => '081200000002',
            'role' => 'bendahara',
            'status_aktif' => true,
            'password' => $password,
            'password_changed' => false,
        ]);

        // Ketua RT
        User::create([
            'name' => 'H. Drs. Sutrisno',
            'username' => 'ketua_rt',
            'nomor_rumah' => 'Kantor RT',
            'telp' => '081200000003',
            'role' => 'ketua_rt',
            'status_aktif' => true,
            'password' => $password,
            'password_changed' => false,
        ]);

        // Warga aktif (50 orang)
        $wargaAktif = [
            ['Budi Santoso',        'budi',        'ET-001', '081234567890'],
            ['Andi Pratama',        'andi',        'ET-002', '081345678901'],
            ['Dani Kurniawan',      'dani',        'ET-003', '082156789012'],
            ['Eko Wahyudi',         'ekow',        'ET-004', '085667890123'],
            ['Fajar Nugroho',       'fajar',       'ET-005', '081278901234'],
            ['Gilang Ramadhan',     'gilang',      'ET-006', '081389012345'],
            ['Hadi Purnomo',        'hadi',        'ET-007', '082190123456'],
            ['Indra Gunawan',       'indra',       'ET-008', '085601234567'],
            ['Joko Susilo',         'joko',        'ET-009', '081212345678'],
            ['Kurniawan Putra',     'kurniawan',   'ET-010', '081323456789'],
            ['Lia Agustina',        'lia',         'ET-011', '082134567890'],
            ['Maya Sari',           'maya',        'ET-012', '085645678901'],
            ['Nisa Handayani',      'nisa',        'ET-013', '081256789012'],
            ['Oki Pratama',         'oki',         'ET-014', '081367890123'],
            ['Putri Rahayu',        'putri',       'ET-015', '082178901234'],
            ['Rina Wati',           'rina',        'ET-016', '085689012345'],
            ['Siti Nurhaliza',      'sitin',       'ET-017', '081290123456'],
            ['Tono Sugiarto',       'tono',        'ET-018', '081301234567'],
            ['Umar Faruk',          'umar',        'ET-019', '082112345678'],
            ['Vina Oktaviani',      'vina',        'ET-020', '085623456789'],
            ['Wati Susilawati',     'wati',        'GT-001', '081234512345'],
            ['Yanto Setiawan',      'yanto',       'GT-002', '081345623456'],
            ['Zaki Mubarak',        'zaki',        'GT-003', '082156734567'],
            ['Arief Budiman',       'arief',       'GT-004', '085667845678'],
            ['Bayu Saputra',        'bayu',        'GT-005', '081278956789'],
            ['Citra Dewi',          'citra',       'GT-006', '081389067890'],
            ['Dian Permata',        'dian',        'GT-007', '082190178901'],
            ['Eka Saputri',         'eka',         'GT-008', '085601289012'],
            ['Ferdi Ardiansyah',    'ferdi',       'GT-009', '081212390123'],
            ['Gita Puspita',        'gita',        'GT-010', '081323401234'],
            ['Hendra Wijaya',       'hendra',      'GT-011', '082134512345'],
            ['Ika Susilawati',      'ika',         'GT-012', '085645623456'],
            ['Jaya Kusuma',         'jaya',        'GT-013', '081256734567'],
            ['Kartika Sari',        'kartika',     'GT-014', '081367845678'],
            ['Luki Firmansyah',     'luki',        'GT-015', '082178956789'],
            ['Mutiara Putri',       'mutiara',     'GT-016', '085689067890'],
            ['Nanda Pratama',       'nanda',       'GT-017', '081290178901'],
            ['Olga Syahputra',      'olga',        'GT-018', '081301289012'],
            ['Rizki Pratama',       'rizki',       'GT-019', '082112390123'],
            ['Sari Dewi',           'sari',        'GT-020', '085623401234'],
            ['Tika Amelia',         'tika',        'H-001', '081234523456'],
            ['Ucok Baba',           'ucok',        'H-002', '081345634567'],
            ['Viona Putri',         'viona',       'H-003', '082156745678'],
            ['Winda Sari',          'winda',       'H-004', '085667856789'],
            ['Yogi Ferdiansyah',    'yogi',        'H-005', '081278967890'],
            ['Zara Cantika',        'zara',        'H-006', '081389078901'],
            ['Adi Nugroho',         'adi',         'H-007', '082190189012'],
            ['Bunga Citra',         'bunga',       'H-008', '085601290123'],
            ['Candra Wijaya',       'candra',      'H-009', '081212301234'],
            ['Dewi Sartika',        'dewi',        'H-010', '081323412345'],
        ];

        // Warga nonaktif (10 orang)
        $wargaNonaktif = [
            ['Eko Prasetyo',        'ekop',        'H-011', '082134523456'],
            ['Fitri Handayani',     'fitri',       'H-012', '085645634567'],
            ['Guntur Setiawan',     'guntur',      'H-013', '081256745678'],
            ['Hana Permata',        'hana',        'H-014', '081367856789'],
            ['Irfan Hakim',         'irfan',       'H-015', '082178967890'],
            ['Jihan Safira',        'jihan',       'H-016', '085689078901'],
            ['Khalifah Putra',      'khalifah',    'H-017', '081290189012'],
            ['Larasati Putri',      'larasati',    'H-018', '081301290123'],
            ['Masruri Hakim',       'masruri',     'H-019', '082112301234'],
            ['Naura Syakira',       'naura',       'H-020', '085623412345'],
        ];

        foreach ($wargaAktif as [$name, $username, $noRumah, $telp]) {
            User::create([
                'name' => $name,
                'username' => $username,
                'nomor_rumah' => $noRumah,
                'telp' => $telp,
                'role' => 'warga',
                'status_aktif' => true,
                'password' => $password,
                'password_changed' => false,
            ]);
        }

        foreach ($wargaNonaktif as [$name, $username, $noRumah, $telp]) {
            User::create([
                'name' => $name,
                'username' => $username,
                'nomor_rumah' => $noRumah,
                'telp' => $telp,
                'role' => 'warga',
                'status_aktif' => false,
                'password' => $password,
                'password_changed' => false,
            ]);
        }
    }
}
