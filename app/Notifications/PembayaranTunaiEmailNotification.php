<?php

namespace App\Notifications;

use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranTunaiEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Iuran $iuran, private ?Pembayaran $pembayaran = null) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        /** @var User $notifiable */
        $bulan = \Carbon\Carbon::create()->month($this->iuran->periode_bulan)->locale('id')->translatedFormat('F');
        $nominal = number_format($this->iuran->nominal, 0, ',', '.');
        $tanggalBayar = $this->pembayaran?->paid_at;

        return (new MailMessage)
            ->subject('Pembayaran Tunai Tercatat - '.config('app.name'))
            ->markdown('emails.pembayaran-tunai', [
                'name' => $notifiable->name,
                'bulan' => $bulan,
                'tahun' => $this->iuran->periode_tahun,
                'nominal' => $nominal,
                'tanggalBayar' => $tanggalBayar
                    ? \Carbon\Carbon::parse($tanggalBayar)->locale('id')->translatedFormat('d F Y')
                    : '-',
                'iuranUrl' => url("/warga/{$this->iuran->user_id}/iuran"),
                'appName' => config('app.name'),
            ]);
    }
}
