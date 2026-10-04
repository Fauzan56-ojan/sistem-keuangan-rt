<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\TunggakanEmailNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KirimTunggakanEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tunggakan:reminder {--sync : Kirim email langsung tanpa antrian (untuk pengetesan)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email pengingat tunggakan iuran ke warga (maksimal 1x per 30 hari per warga)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('sync')) {
            config(['queue.default' => 'sync']);
        }

        $tahunSekarang = now()->year;
        $bulanSekarang = now()->month;

        $warga = User::where('role', 'warga')
            ->where('status_aktif', 1)
            ->whereNotNull('email')
            ->where('email', 'not like', '%@histori.local')
            ->with(['iuran' => function ($q) use ($tahunSekarang, $bulanSekarang) {
                $q->where('status', 'pending')
                    ->where(function ($q2) use ($tahunSekarang, $bulanSekarang) {
                        $q2->where('periode_tahun', '<', $tahunSekarang)
                            ->orWhere(function ($q3) use ($tahunSekarang, $bulanSekarang) {
                                $q3->where('periode_tahun', $tahunSekarang)
                                    ->where('periode_bulan', '<', $bulanSekarang);
                            });
                    });
            }])
            ->get()
            ->filter(fn ($user) => $user->iuran->isNotEmpty());

        if ($warga->isEmpty()) {
            $this->info('Tidak ada warga dengan tunggakan.');

            return self::SUCCESS;
        }

        $sudahDikirim = DB::table('notifications')
            ->where('type', TunggakanEmailNotification::class)
            ->where('notifiable_type', User::class)
            ->whereIn('notifiable_id', $warga->pluck('id'))
            ->where('created_at', '>=', now()->subDays(30))
            ->pluck('notifiable_id')
            ->flip();

        $terkirim = 0;
        $dilewati = 0;
        $gagal = 0;

        foreach ($warga as $user) {
            if ($sudahDikirim->has($user->id)) {
                $dilewati++;
                continue;
            }

            $jumlahBulan = $user->iuran->count();
            $total = (int) $user->iuran->sum('nominal');

            try {
                $user->notify(new TunggakanEmailNotification($jumlahBulan, $total));
                $terkirim++;
            } catch (\Throwable $e) {
                $gagal++;
                \Log::error("Gagal kirim email tunggakan ke {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Tunggakan ditemukan: {$warga->count()} warga.");
        $this->info("Dikirim: {$terkirim} | Dilewati (baru dikirim <30 hari): {$dilewati} | Gagal: {$gagal}");

        return self::SUCCESS;
    }
}
