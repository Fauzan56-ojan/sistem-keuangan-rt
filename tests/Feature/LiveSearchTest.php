<?php

namespace Tests\Feature;

use App\Models\Iuran;
use App\Models\Pemasukan;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LiveSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withViewErrors([]);

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            DB::table('setnominal')->insert([
                'bulan' => $bulan,
                'tahun' => now()->year,
                'nominal' => 100000,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function warga(string $name, string $rumah): User
    {
        return User::factory()->create([
            'name' => $name,
            'role' => 'warga',
            'nomor_rumah' => $rumah,
        ]);
    }

    /**
     * Pastikan setiap elemen data-live-target tertutup dengan benar
     * dan tidak memuat input pencarian (agar fokus tidak hilang saat mengetik).
     */
    private function assertTargetsAreBalanced(string $html): void
    {
        preg_match_all('/<div[^>]*data-live-target="([^"]+)"[^>]*>/', $html, $matches, PREG_OFFSET_CAPTURE);

        $this->assertNotEmpty($matches[0], 'Elemen data-live-target tidak ditemukan pada hasil render.');

        foreach ($matches[0] as $i => $opening) {
            $key = $matches[1][$i][0];
            $pos = $opening[1] + 4;
            $depth = 1;
            $closedAt = null;

            while (($close = strpos($html, '</div>', $pos)) !== false) {
                $open = strpos($html, '<div', $pos);

                if ($open !== false && $open < $close) {
                    $depth++;
                    $pos = $open + 4;
                    continue;
                }

                if ($depth === 1) {
                    $closedAt = $close;
                    break;
                }

                $depth--;
                $pos = $close + 6;
            }

            $this->assertNotNull($closedAt, "Komponen \"{$key}\" tidak tertutup dengan benar.");

            $inner = substr($html, $opening[1], $closedAt + 6 - $opening[1]);
            $this->assertStringNotContainsString(
                'data-live-search',
                $inner,
                "Input pencarian tidak boleh berada di dalam region \"{$key}\"."
            );
        }
    }

    public function test_all_search_pages_render_live_search_hooks(): void
    {
        $admin = $this->admin();

        $budi = $this->warga('Budi Santoso', 'ET-1');
        $siti = $this->warga('Siti Aminah', 'ET-2');

        $iuran = Iuran::create([
            'user_id' => $budi->id,
            'periode_bulan' => now()->month,
            'periode_tahun' => now()->year,
            'nominal' => 100000,
            'status' => 'paid',
        ]);

        Pembayaran::create([
            'iuran_id' => $iuran->id,
            'user_id' => $budi->id,
            'amount' => 100000,
            'metode' => 'tunai',
            'status' => 'success',
            'paid_at' => now(),
        ]);

        Pemasukan::create([
            'tanggal' => now()->toDateString(),
            'nominal' => 50000,
            'keterangan' => 'Donasi Kampung',
            'created_by' => $admin->id,
        ]);

        Pengeluaran::create([
            'tanggal' => now()->toDateString(),
            'nominal' => 25000,
            'keterangan' => 'Beli Lampu Jalan',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin);

        $pages = [
            'users.index' => ['users' => new LengthAwarePaginator([$budi, $siti], 2, 10)],
            'iuran.warga-list' => [
                'users' => new LengthAwarePaginator([$budi, $siti], 2, 10),
                'sudahBayar' => 1,
                'belumBayar' => 1,
            ],
            'pembayaran.riwayat' => [
                'data' => new LengthAwarePaginator([$iuran->pembayaran->first()], 1, 10),
                'tahunList' => [now()->year],
            ],
            'pemasukan.index' => [
                'data' => new LengthAwarePaginator(Pemasukan::all(), 1, 10),
                'tahunList' => [now()->year],
            ],
            'pengeluaran.index' => [
                'data' => new LengthAwarePaginator(Pengeluaran::all(), 1, 10),
                'tahunList' => [now()->year],
            ],
        ];

        foreach ($pages as $view => $data) {
            $html = view($view, $data)->render();

            $this->assertStringContainsString('data-live-search', $html, "Hook live search hilang di {$view}.");
            $this->assertStringContainsString('data-live-target', $html, "Region live search hilang di {$view}.");
            $this->assertTargetsAreBalanced($html);
        }
    }

    public function test_users_search_filters_and_empty_search_restores_all(): void
    {
        $admin = $this->admin();
        $this->warga('Andi Saputra', 'ET-1');
        $this->warga('Dewi Lestari', 'ET-2');

        $this->actingAs($admin)->get('/users')
            ->assertOk()
            ->assertSee('data-live-search')
            ->assertSee('Andi Saputra')
            ->assertSee('Dewi Lestari');

        $this->actingAs($admin)->get('/users?search=Andi')
            ->assertOk()
            ->assertSee('Andi Saputra')
            ->assertDontSee('Dewi Lestari');

        $this->actingAs($admin)->get('/users?search=ET-2')
            ->assertOk()
            ->assertSee('Dewi Lestari')
            ->assertDontSee('Andi Saputra');

        $this->actingAs($admin)->get('/users?search=')
            ->assertOk()
            ->assertSee('Andi Saputra')
            ->assertSee('Dewi Lestari');

        $this->actingAs($admin)->get('/users?search=zzz-tidak-ada')
            ->assertOk()
            ->assertDontSee('Andi Saputra')
            ->assertDontSee('Dewi Lestari');
    }

    public function test_iuran_warga_search_filters_and_empty_search_restores_all(): void
    {
        $admin = $this->admin();
        $this->warga('Budi Santoso', 'ET-1');
        $this->warga('Siti Aminah', 'ET-2');

        $this->actingAs($admin)->get('/iuran-warga')
            ->assertOk()
            ->assertSee('data-live-search')
            ->assertSee('Budi Santoso')
            ->assertSee('Siti Aminah');

        $this->actingAs($admin)->get('/iuran-warga?search=Budi')
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertDontSee('Siti Aminah');

        $this->actingAs($admin)->get('/iuran-warga?search=')
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee('Siti Aminah');
    }

    public function test_search_keyword_survives_pagination_links(): void
    {
        $admin = $this->admin();

        foreach (range(1, 12) as $i) {
            $this->warga("Warga Nomor {$i} Contoh", sprintf('ET-%02d', $i));
        }

        $response = $this->actingAs($admin)->get('/users?search=Contoh&page=2');

        $response->assertOk();
        $response->assertSee('search=Contoh', false);
    }
}
