<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    protected $table = 'pemasukan';

    protected $fillable = [
        'tanggal',
        'nominal',
        'keterangan',
        'created_by'
    ];

        public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}