<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\search;

class SatuanController extends Controller
{
    public function index()
    {
        // ambil data satuan dari view
        $sql = 'SELECT DISTINCT * FROM satuan_vu  ORDER BY idsatuan';
        $satuans = DB::select($sql);
        // ambil data satuan dari tabel untuk dropdown
        $dataSatuans = DB::select('SELECT * FROM satuan ORDER BY idsatuan');

        
        return view('manage_satuan.manage_satuan', compact('satuans', 'dataSatuans'));
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
