<?php

namespace App\Services;

use App\Models\Iuran;

class NotificationService
{
    public static function getNotifData($user)
    {
        $notifPassword = !$user->password_changed;

        $notifTunggakan = Iuran::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where(function ($q) {

                $q->where('periode_tahun', '<', now()->year)

                ->orWhere(function ($q2) {
                    $q2->where('periode_tahun', now()->year)
                        ->where('periode_bulan', '<', now()->month);
                });

            })
            ->exists();

        $count = 0;

        if ($notifPassword) {
            $count++;
        }

        if ($notifTunggakan) {
            $count++;
        }

        return [
            'count' => $count,
            'password' => $notifPassword,
            'tunggakan' => $notifTunggakan,
        ];
    }
}