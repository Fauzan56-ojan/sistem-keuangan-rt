<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'user_id',
        'iuran_id',
        'amount',
        'metode',
        'order_id',
        'snap_token',
        'paid_at',
        'status'
        ];

    public function user()
    
    {
        return $this->belongsTo(User::class);
    }

    public function iuran()
    {
        return $this->belongsTo(Iuran::class);
    }
}
