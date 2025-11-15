<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStok extends Controller
{
    public function index()
    {
        // retrieve kartu_stok rows and include related barang info via join
        $kartu_stoks = DB::table('kartu_stok')
            ->leftJoin('barang', 'kartu_stok.idbarang', '=', 'barang.idbarang')
            ->select('kartu_stok.*', 'barang.*')
            ->get();

        return view('kartu_stok.kartu_stok', compact('kartu_stoks'));
    }
}
