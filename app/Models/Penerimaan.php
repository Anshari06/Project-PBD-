<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $table = 'penerimaan';
    protected $primaryKey = 'idpenerimaan';

    public function pengadaan()
    {
        // return $this->belongsTo(Pengadaan::class, 'idpengadaan', 'idpengadaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }


}
