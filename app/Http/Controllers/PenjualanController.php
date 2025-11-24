<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = DB::table('penjualan_barang')
            ->orderBy('idpenjualan', 'Asc')
            ->get();

        // return only the list of idbarang values (e.g. [1,2,3])
        $barangs = DB::table('kartu_stock_barang')
            ->select('idbarang', 'nama_barang')
            ->distinct()
            ->where('stock', '>', 0)
            ->get();

        return view('Penjualan.manage_penjualan', compact('penjualans', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idbarang' => 'required|array',
            'jumlah'   => 'required|array'
        ]);

        $idbarang = $request->input('idbarang');   // array
        $jumlah   = $request->input('jumlah');     // array

        // Pastikan panjang array sama
        if (count($idbarang) !== count($jumlah)) {
            return back()->withInput()->with('error', 'Jumlah barang dan kuantitas tidak cocok.');
        }

        // Convert array → string list: "1,3,10"
        $idbarangList = implode(',', $idbarang);
        $jumlahList   = implode(',', $jumlah);
        $iduser = Auth::id();
        $ppn = 11;

        try {
            DB::beginTransaction();

            DB::statement('CALL tambah_penjualan(?, ?, ?, ?)', [
                $iduser,        // p_iduser
                $ppn,           // p_persen_ppn
                $idbarangList,  // p_idbarang_list
                $jumlahList     // p_jumlah_list
            ]);

            DB::commit();
            return back()->with('success', 'Penjualan berhasil ditambahkan!');
        } catch (\Exception $e) {

            DB::rollBack();
            logger()->error('Tambah penjualan gagal: ' . $e->getMessage(), [
                'payload' => $request->all()
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function show($id)
    {
        // Try to load penjualan header from penjualan table (fallbacks handled in view)
        $penjualan = DB::table('penjualan_barang')->where('idpenjualan', $id)->first();

        if (! $penjualan) {
            return redirect('/manage_penjualan')->with('error', 'Penjualan tidak ditemukan.');
        }

        // Load detail rows joined with barang to provide `nama_barang` and `harga` fields
        $detailPenjualans = DB::table('detail_penjualan as det')
            ->leftJoin('barang as b', 'det.idbarang', '=', 'b.idbarang')
            ->select('det.*', 'b.nama as nama_barang', 'b.harga')
            ->where('det.idpenjualan', $id)
            ->get();

        // Provide view
        return view('Penjualan.detail_penjualan', compact('penjualan', 'detailPenjualans'));
    }
}
