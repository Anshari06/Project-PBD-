<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BarangController extends Controller
{
    public function index(Request $request)
    {
        $sql = 'SELECT * FROM barang_satuan_stock ORDER BY idbarang';
        $barangs = DB::select($sql);

        // ini buat nangkep inputan dari form
        $cariBarangs = [];

        $nama_barang = $request->input('nama_barang');
        $cariBarangs = DB::select('CALL cari_barang(?)', [$nama_barang]);

        $jenis_barang = $request->input('jenis_barang');
        $HitungBarang = null;
        if (!empty($jenis_barang)) {
            $res = DB::select('SELECT get_total_barang_by_jenis(?) AS total_barang', [$jenis_barang]);
            // DB::select returns array of stdClass; extract scalar safely
            if (!empty($res) && isset($res[0]->total_barang)) {
                $HitungBarang = (int) $res[0]->total_barang;
            } else {
                $HitungBarang = 0;
            }
        }


        


        return view('manage_barang.manage_barang', compact('barangs', 'cariBarangs', 'HitungBarang'));
    }
}
