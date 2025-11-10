<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function index()
    {
        // eager-load user relation so we can access $pengadaan->user->username in the view
        $pengadaans = Pengadaan::all();
        $detailPengadaans = DB::select('SELECT * FROM Detail_Pengadaan_VU');
        $vendors = DB::table('vendor_aktif')->get();
        $users = User::all();
        $barangs = DB::table('barang_aktif')->get();

        // pass the variable to the view (use lowercase variable name to match blade)
        return view('Pengadaan.manage_pengadaan', compact('pengadaans', 'users', 'vendors', 'barangs', 'detailPengadaans'));
    }

    public function store(Request $request)
    {
        DB::insert("
            INSERT INTO pengadaan (idvendor, iduser, status, total_nilai, subtotal_nilai)
                VALUES (?, ?, ?, ?, hitung_subtotal_ppn(?,?))", [
            $request->input('idvendor'),
            $request->input('iduser'),
            $request->input('status'),
            $request->input('total_nilai'),
            $request->input('total_nilai'),
            11 // persen PPN
        ]);
        DB::insert("
            INSERT INTO detail_pengadaan (idbarang, harga_satuan, jumlah, sub_total, idpengadaan)
                VALUES (?, (SELECT harga from barang where idbarang=?) , ?, hitung_subtotal(?, (SELECT harga from barang where idbarang=?)), ?)",
            [
                $request->input('idbarang'),
                $request->input('idbarang'),
                $request->input('jumlah'),
                $request->input('jumlah'),
                DB::getPdo()->lastInsertId()
            ]
            );


        return redirect()->route('pengadaan.manage_pengadaan')->with('success', 'Pengadaan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $detailPengadaans = DB::select('SELECT * FROM Detail_Pengadaan_VU WHERE idpengadaan = ?', [$id]);

        return view('Pengadaan.detail_pengadaan', compact('pengadaan', 'detailPengadaans'));
    }
}
