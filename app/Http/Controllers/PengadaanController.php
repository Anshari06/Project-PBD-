<?php

namespace App\Http\Controllers;

// use App\Models\Barang;
use App\Models\Pengadaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function index()
    {
        // eager-load user relation so we can access $pengadaan->user->username in the view
        $pengadaans = DB::select('SELECT * FROM Pengadaan_barang order BY idpengadaan');
        $detailPengadaans = DB::select('SELECT * FROM Detail_Pengadaan_VU');
        $vendors = DB::table('vendor_aktif')->get();
        $users = User::all();
        $barangs = DB::table('barang_aktif')->get();

        // pass the variable to the view (use lowercase variable name to match blade)
        return view('Pengadaan.manage_pengadaan', compact('pengadaans', 'users', 'vendors', 'barangs', 'detailPengadaans'));
    }

    public function store(Request $request)
    {
        // create pengadaan header
        DB::insert(
            "INSERT INTO pengadaan (idvendor, iduser, status, total_nilai, subtotal_nilai) VALUES (?, ?, ?, 0, 0)",
            [
                $request->input('idvendor'),
                Auth::id(),
                $request->input('status'),
            ]
        );

        $idpengadaan = DB::getPdo()->lastInsertId();

        // insert multiple detail_pengadaan rows from arrays
        $idbarangs = $request->input('idbarang', []);
        $jumlahs = $request->input('jumlah', []);

        foreach ($idbarangs as $i => $idb) {
            $jumlah = isset($jumlahs[$i]) ? intval($jumlahs[$i]) : 0;
            if (empty($idb) || $jumlah <= 0) {
                continue; // skip empty rows
            }

            DB::insert(
                "INSERT INTO detail_pengadaan (idbarang, harga_satuan, jumlah, sub_total, idpengadaan) VALUES (?, (SELECT harga FROM barang WHERE idbarang = ?), ?, hitung_subtotal(?, (SELECT harga FROM barang WHERE idbarang = ?)), ?)",
                [
                    $idb,
                    $idb,
                    $jumlah,
                    $jumlah,
                    $idb,
                    $idpengadaan,
                ]
            );
        }

        DB::update(
            "
            UPDATE pengadaan 
            SET subtotal_nilai = (
                SELECT SUM(sub_total) 
                FROM detail_pengadaan 
                WHERE detail_pengadaan.idpengadaan = pengadaan.idpengadaan
            )
            WHERE idpengadaan = ?",
            [$idpengadaan]
        );
        DB::update("
            UPDATE pengadaan
            SET total_nilai = hitung_total_ppn(subtotal_nilai, 11)
            WHERE idpengadaan = ?", [$idpengadaan]);


        return redirect()->route('pengadaan.manage_pengadaan')->with('success', 'Pengadaan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pengadaan = collect(DB::select('SELECT * FROM Detail_Pengadaan_VU WHERE idpengadaan = ?', [$id]))->first();
        $detailPengadaans = DB::select('SELECT * FROM detpengadaan_barang WHERE idpengadaan = ?', [$id]);

        return view('Pengadaan.detail_pengadaan', compact('pengadaan', 'detailPengadaans'));
    }

    public function destroy($id)
    {
        // Delete related detail_pengadaan records first
        DB::table('detail_pengadaan')->where('idpengadaan', $id)->delete();

        // Then delete the pengadaan record
        DB::table('pengadaan')->where('idpengadaan', $id)->delete();

        return redirect()->route('pengadaan.manage_pengadaan')->with('success', 'Pengadaan berhasil dihapus.');
    }
}
