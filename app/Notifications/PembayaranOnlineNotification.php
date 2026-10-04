<?php

namespace App\Notifications;

use App\Models\Iuran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranOnlineNotification extends Notification
{
    use Queueable;

    public function __construct(private Iuran $iuran, private string $untuk = 'bendahara') {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, string>
     */
    public function toArray(object $notifiable): array
    {
        $bulan = \Carbon\Carbon::create()->month($this->iuran->periode_bulan)->locale('id')->translatedFormat('F');
        $nominal = number_format($this->iuran->nominal, 0, ',', '.');

        if ($this->untuk === 'warga') {
            return [
                'title' => 'Pembayaran Online Berhasil',
                'message' => "Pembayaran iuran bulan {$bulan} {$this->iuran->periode_tahun} sebesar Rp {$nominal} telah berhasil.",
                'url' => "/warga/{$this->iuran->user_id}/iuran",
                'icon' => 'credit_card',
            ];
        }

        return [
            'title' => 'Pembayaran Online',
            'message' => "Warga {$this->iuran->user->name} (Rumah {$this->iuran->user->nomor_rumah}) telah membayar iuran bulan {$bulan} {$this->iuran->periode_tahun} secara online.",
            'url' => "/warga/{$this->iuran->user_id}/iuran",
            'icon' => 'credit_card',
        ];
    }
}
