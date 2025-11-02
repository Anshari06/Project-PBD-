<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail_Penjualan extends Model
{
    protected $table = 'detail_penjualan';

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
