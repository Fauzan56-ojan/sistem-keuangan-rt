<?php

namespace App\Notifications;

use App\Models\Iuran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranTunaiNotification extends Notification
{
    use Queueable;

    public function __construct(private Iuran $iuran) {}

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

        return [
            'title' => 'Pembayaran Tunai Dicatat',
            'message' => "Pembayaran iuran bulan {$bulan} {$this->iuran->periode_tahun} sebesar Rp " . number_format($this->iuran->nominal, 0, ',', '.') . " telah dicatat oleh bendahara.",
            'url' => "/warga/{$this->iuran->user_id}/iuran",
            'icon' => 'payments',
        ];
    }
}
