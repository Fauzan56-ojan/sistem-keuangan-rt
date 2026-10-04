<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TunggakanEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private int $jumlahBulan, private int $total) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        /** @var User $notifiable */
        return (new MailMessage)
            ->subject('Pengingat Tunggakan Iuran - '.config('app.name'))
            ->markdown('emails.tunggakan', [
                'name' => $notifiable->name,
                'jumlahBulan' => $this->jumlahBulan,
                'total' => number_format($this->total, 0, ',', '.'),
                'tunggakanUrl' => url("/tunggakan/{$notifiable->id}"),
                'appName' => config('app.name'),
            ]);
    }
}
