<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
    // Ambil daftar satuan unik (satu baris per idsatuan) dari view.
    // Pilih kolom yang diperlukan agar DISTINCT bekerja dengan benar.
    $satuans = collect(DB::select('SELECT DISTINCT idsatuan, nama_satuan, Status FROM satuan_vu ORDER BY idsatuan'));
    
    $dataSatuans = collect(DB::select('SELECT * FROM satuan'));

    // Daftar unik untuk dropdown (idsatuan + nama)
    $idsatua= $request->query('idsatuan'); // atau $request->input('idsatuan')
    $satuanByID = collect(DB::select('CALL lihat_barang_per_satuan(?)', [$idsatua]));

    return view('manage_satuan.manage_satuan', compact('satuans', 'dataSatuans', 'satuanByID'));
    }

    // public function ShowData(Request $request)
    // {
    //     $sql = 'SELECT * FROM satuan_vu';
    //     $satuans = DB::select($sql);
    //     // ambil data satuan dari tabel untuk dropdown
    //     $dataSatuans = DB::select('SELECT * FROM satuan ORDER BY idsatuan');

    //     // dapatkan idsatuan dari input form
    //     $idsatuan = $request->input('idsatuan');

    //     // ambil data satuan berdasarkan idsatuan yang dipilih
    //     $sql = 'SELECT * FROM satuan_vu WHERE idsatuan = ?';
    //     $filterSatuans = DB::select($sql, [$idsatuan]);


    //     return view('manage_satuan.manage_satuan', compact('satuans', 'dataSatuans', 'filterSatuans'));
    // }
}
