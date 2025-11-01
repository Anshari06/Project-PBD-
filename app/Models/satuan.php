<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class satuan extends Model
{
    protected $table = 'satuan';

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'idsatuan');
    }
}
