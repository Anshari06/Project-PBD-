<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BarangController extends Controller
{
    public function index()
    {
        $sql = 'SELECT * FROM barang_satuan_vu ORDER BY idbarang';
        $barangs = DB::select($sql);

    // Blade file currently lives at resources/views/manage_barang.blade.php
    // so return the flat view name 'manage_barang'
    return view('manage_barang.manage_barang', compact('barangs'));
    }
}
