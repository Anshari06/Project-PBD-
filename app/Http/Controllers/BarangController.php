<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $sql = 'SELECT * FROM barang_satuan_vu ORDER BY idbarang';
        $barangs = DB::select($sql);

        // ini buat nangkep inputan dari form
        $cariBarangs = [];

        $nama_barang = $request->input('nama_barang');
        $cariBarangs = DB::select('CALL cari_barang(?)', [$nama_barang]);


        return view('manage_barang.manage_barang', compact('barangs', 'cariBarangs'));
    }
}
