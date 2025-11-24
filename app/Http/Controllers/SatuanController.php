<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        // allow toggling between view of satuan used by products (default) and all satuan via ?show_all=1
        $showAll = $request->boolean('show_all');

        if ($showAll) {
            // all satuan definitions
            $satuans = collect(DB::select('SELECT * FROM satuan ORDER BY idsatuan'));
        } else {
            // default: satuan as shown in barang_satuan_stock_status view
            $satuans = collect(DB::select('SELECT DISTINCT * FROM satuan ORDER BY idsatuan'));
        }

        $dataSatuans = collect(DB::select('SELECT * FROM satuan'));

        // Daftar unik untuk dropdown (idsatuan + nama)
        $idsatua = $request->query('idsatuan'); // atau $request->input('idsatuan')
        $satuanByID = collect(DB::select('CALL lihat_barang_per_satuan(?)', [$idsatua]));

        return view('manage_satuan.manage_satuan', compact('satuans', 'dataSatuans', 'satuanByID', 'showAll'));
    }

    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'nama_satuan' => 'required|string|max:255',
    //     ]);

    //     // Simpan data satuan baru ke database
    //     DB::table('satuan')->insert([
    //         'nama_satuan' => $request->input('nama_satuan'),
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     // Redirect kembali ke halaman manage_satuan dengan pesan sukses
    //     return redirect('/manage_satuan')->with('success', 'Satuan baru berhasil ditambahkan.');
    // }


}
