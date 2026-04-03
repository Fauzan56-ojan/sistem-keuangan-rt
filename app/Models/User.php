<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'nomor_rumah',
        'role',
        'status_aktif',
        'password'
    ];

    public function iuran()
    {
        return $this->hasMany(Iuran::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'created_by');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'created_by');
    }

    protected static function booted()
    {
        static::created(function ($user) {

                if ($user->role !== 'warga') {
                return;
            }

            $nominal = \DB::table('setnominal')
                ->where('tahun', date('Y'))
                ->value('nominal');

            for ($i = 1; $i <= 12; $i++) {

                \App\Models\Iuran::create([
                    'user_id' => $user->id,
                    'periode_bulan' => $i,
                    'periode_tahun' => date('Y'),
                    'nominal' => $nominal,
                    'status' => 'pending'
                ]);

            }

        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
