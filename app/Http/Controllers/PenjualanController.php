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
        // The form uses `tanggal`, `idbarang[]` and `jumlah[]`.
        $request->validate([
            'tanggal'   => 'required|date',
            'idbarang'  => 'required',
            'jumlah'    => 'required'
        ]);

        // Normalize to arrays (support single-row or multiple-row submissions)
        $idbarang = $request->input('idbarang');
        $jumlah = $request->input('jumlah');
        if (!is_array($idbarang)) $idbarang = [$idbarang];
        if (!is_array($jumlah)) $jumlah = [$jumlah];

        if (count($idbarang) !== count($jumlah)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah barang dan kuantitas tidak cocok.');
        }

        // Default PPN if not provided in the form
        $ppn = (int) $request->input('persen_ppn', 0);
        $tgl = $request->input('tanggal');
        $iduser = Auth::id();

        try {
            DB::beginTransaction();

            // Call stored procedure for each item. Current SP creates its own header.
            foreach ($idbarang as $i => $bid) {
                $qty = (int) $jumlah[$i];
                if ($qty <= 0) {
                    throw new \Exception('Jumlah harus lebih besar dari 0 pada baris ' . ($i + 1));
                }

                DB::statement('CALL tambah_penjualan(?, ?, ?, ?, ?)', [
                    $iduser,
                    $tgl,
                    $ppn,
                    (int) $bid,
                    $qty
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penjualan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Tambah penjualan failed: ' . $e->getMessage(), ['payload' => $request->all()]);
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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
