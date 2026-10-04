<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AkunDibuatNotification extends Notification
{
    use Queueable;

    private string $plainPassword;

    public function __construct(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        /** @var User $notifiable */
        return (new MailMessage)
            ->subject('Akun Anda Telah Dibuat - '.config('app.name'))
            ->markdown('emails.akun-dibuat', [
                'name' => $notifiable->name,
                'email' => $notifiable->email,
                'password' => $this->plainPassword,
                'appName' => config('app.name'),
                'loginUrl' => route('login'),
            ]);
    }

    /**
     * @return array<string, string>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
