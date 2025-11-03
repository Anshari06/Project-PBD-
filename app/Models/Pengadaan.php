<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pengadaan extends Model
{
    protected $table = 'pengadaan';

    // if primary key column is not 'id'
    protected $primaryKey = 'idpengadaan';

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }

    /**
     * Relation to the user who created / owns this pengadaan
     */
    public function user()
    {
        // assumes `iduser` is the FK in `pengadaan` and `id` is PK on users table
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}
