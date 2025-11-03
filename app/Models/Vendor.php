<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    // if primary key column is not 'id'
    protected $primaryKey = 'idvendor';

    
}
