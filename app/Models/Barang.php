<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    // if primary key column is not 'id'
    protected $primaryKey = 'idbarang';

    // disable timestamps if the table doesn't have created_at/updated_at
    public $timestamps = false;

    // relation to Satuan: use method name 'satuan' so you can access $barang->satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'idsatuan', 'idsatuan');
    }
}
