<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $penerimaans = DB::table('pengadaan_penerimaan')
        ->orderBy('idpenerimaan', 'Asc')
        ->get();

        // also provide pengadaan list and users for the add penerimaan form
        $pengadaans = DB::table('pengadaan_barang')
            ->where('status', '!=', 'S')
            ->orderBy('idpengadaan')
            ->get();

        $barangs = DB::table('barang_aktif')->get();

        return view('penerimaan.manage_penerimaan', compact('penerimaans', 'pengadaans', 'barangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idpengadaan' => 'required|integer|exists:pengadaan,idpengadaan',
            'idbarang' => 'required|integer|exists:barang,idbarang',
            'jumlah_terima' => 'required|integer|min:1',
            'tgl_penerimaan' => 'nullable|date',
            'status_penerimaan' => 'nullable|string|in:P,O,S,B',
        ]);

        $idpengadaan = $data['idpengadaan'];
        $idbarang = $data['idbarang'];
        $jumlah_terima = $data['jumlah_terima'];
        $status = $data['status_penerimaan'] ?? 'P';

        // prefer server-side authenticated user id instead of trusting client input
        $iduser = Auth::id() ?? $request->input('iduser');

        try {
            // Stored procedure signature expects: p_idpengadaan, p_idbarang, p_jumlah_terima, p_iduser, p_status
            DB::select("CALL Tambah_penerimaan(?, ?, ?, ?, ?)", [
                $idpengadaan,
                $idbarang,
                $jumlah_terima,
                $iduser,
                $status,
            ]);

            return redirect('/manage_penerimaan')->with('success', 'Penerimaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            // log error and show friendly message
            logger()->error('Tambah_penerimaan failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambah penerimaan: ' . $e->getMessage());
        }
    }
}
