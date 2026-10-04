<?php

namespace App\Notifications;

use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranOnlineEmailNotification extends Notification implements ShouldQueue
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
        $metode = $this->metodeLabel();

        return (new MailMessage)
            ->subject('Pembayaran Online Berhasil - '.config('app.name'))
            ->markdown('emails.pembayaran-online', [
                'name' => $notifiable->name,
                'bulan' => $bulan,
                'tahun' => $this->iuran->periode_tahun,
                'nominal' => $nominal,
                'metode' => $metode,
                'tanggalBayar' => $tanggalBayar
                    ? \Carbon\Carbon::parse($tanggalBayar)->locale('id')->translatedFormat('d F Y')
                    : '-',
                'iuranUrl' => url("/warga/{$this->iuran->user_id}/iuran"),
                'appName' => config('app.name'),
            ]);
    }

    private function metodeLabel(): string
    {
        $metode = (string) ($this->pembayaran?->metode ?? 'online');

        return match ($metode) {
            'bank_transfer' => 'Transfer Bank',
            'echannel' => 'Mandiri Bill',
            'gopay', 'shopeepay', 'qris', 'dana', 'ovo', 'linkaja' => strtoupper($metode),
            'cstore' => 'Gerai Retail',
            'indomaret' => 'Indomaret',
            'alfamart' => 'Alfamart',
            default => ucwords(str_replace('_', ' ', $metode)),
        };
    }
}
