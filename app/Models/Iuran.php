<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{   
    protected $table = 'iuran';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    protected $fillable = [
        'user_id',
        'periode_bulan',
        'periode_tahun',
        'nominal',
        'status'
    ];
}
