<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';

    // if your primary key column name is not 'id', set it here
    protected $primaryKey = 'idsatuan';

    // disable timestamps if the table doesn't have created_at/updated_at
    public $timestamps = false;

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'idsatuan', 'idsatuan');
    }
}
