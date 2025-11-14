<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $penerimaans = DB::table('pengadaan_penerimaan')->get();

        // also provide pengadaan list and users for the add penerimaan form
        $pengadaans = DB::select('SELECT * FROM Pengadaan_barang order BY idpengadaan');
        $users = User::all();

        return view('penerimaan.manage_penerimaan', compact('penerimaans', 'pengadaans', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idpengadaan' => 'required|integer',
            'tgl_penerimaan' => 'nullable|date',
            'status_penerimaan' => 'nullable|in:0,1',
            'iduser' => 'required|integer',
        ]);

        // get total_nilai from pengadaan if available
        $pengadaan = DB::table('pengadaan')->where('idpengadaan', $data['idpengadaan'])->first();
        $total_nilai = $pengadaan->total_nilai ?? 0;

        DB::insert("INSERT INTO penerimaan (idpengadaan, tgl_penerimaan, total_nilai, status_penerimaan, iduser, created_at)
            VALUES (?, ?, ?, ?, ?, ?)", [
            $data['idpengadaan'],
            $data['tgl_penerimaan'] ?? now(),
            $total_nilai,
            $data['status_penerimaan'] ?? 0,
            $data['iduser'],
            now(),
        ]);

        return redirect('/manage_penerimaan')->with('success', 'Penerimaan berhasil ditambahkan.');
    }
}
