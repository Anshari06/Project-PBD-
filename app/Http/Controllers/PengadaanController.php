<?php

namespace App\Http\Controllers;

// use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function index()
    {
        // eager-load user relation so we can access $pengadaan->user->username in the view
        $pengadaans = DB::select('SELECT DISTINCT idpengadaan, created_at, nama_vendor, username, ppn, total_nilai, subtotal_nilai, status FROM Pengadaan_barang ORDER BY idpengadaan');
        $detailPengadaans = DB::select('SELECT * FROM Detail_Pengadaan_VU');
        $vendors = DB::table('vendor_aktif')->get();
        $users = User::all();
        $barangs = DB::table('barang_aktif')->get();

        // pass the variable to the view (use lowercase variable name to match blade)
        return view('Pengadaan.manage_pengadaan', compact('pengadaans', 'users', 'vendors', 'barangs', 'detailPengadaans'));
    }

    public function store(Request $request)
    {
        // server-side validation: require arrays and jumlah >= 1
        $request->validate([
            'idvendor' => 'required|integer',
            'idbarang' => 'required|array|min:1',
            'idbarang.*' => 'required|integer',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ], [
            'jumlah.*.min' => 'Jumlah barang harus minimal 1 (tidak boleh 0).',
            'jumlah.*.integer' => 'Jumlah harus berupa angka bulat.',
        ]);

        $idbarang = $request->input('idbarang');
        $jumlah = $request->input('jumlah');

        // ensure arrays have same length
        if (count($idbarang) !== count($jumlah)) {
            return back()->withInput()->with('error', 'Jumlah item dan daftar barang tidak cocok.');
        }

        // Convert array ke string "1,2,3"
        $idbarang_list = implode(',', $idbarang);
        $jumlah_list = implode(',', $jumlah);

        $result = DB::select("CALL tambah_pengadaan(?, ?, ?, ?)", [
            $request->idvendor,
            Auth::id(),
            $idbarang_list,
            $jumlah_list
        ]);

        $idpengadaan = $result[0]->idpengadaan ?? null;

        return redirect()->route('pengadaan.manage_pengadaan')
            ->with('success', 'Pengadaan berhasil dibuat dengan SP');
    }


    public function show($id)
    {
        $pengadaan = DB::table('Detail_Pengadaan_VU')
            ->where('idpengadaan', $id)
            ->first();
        $detailPengadaan = DB::table('Detail_Pengadaan_VU')
            ->where('idpengadaan', $id)
            ->get();
        $detailbarang = DB::select('SELECT * FROM detpengadaan_barang WHERE idpengadaan = ?', [$id]);

        return view('Pengadaan.detail_pengadaan', compact('pengadaan', 'detailPengadaan', 'detailbarang'));
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
