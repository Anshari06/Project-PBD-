<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}
