<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // Prefer driver message (errorInfo[2]) when available - this will contain the SIGNAL text
            $errorInfo = $e->errorInfo ?? null;
            $sqlState = is_array($errorInfo) && isset($errorInfo[0]) ? $errorInfo[0] : null;
            $driverMsg = is_array($errorInfo) && isset($errorInfo[2]) ? $errorInfo[2] : $e->getMessage();

            // If the stored procedure used SIGNAL/RESIGNAL with SQLSTATE '45000', show that message to user
            if ($sqlState === '45000') {
                $friendly = $driverMsg; // e.g. 'Stok tidak mencukupi untuk transaksi ini.'
            } else {
                // Map common DB errors to friendlier messages
                if (stripos($driverMsg, 'Truncated incorrect INTEGER value') !== false) {
                    $friendly = 'Nilai numerik salah atau terlalu besar pada salah satu input. Periksa kolom ID barang atau jumlah.';
                } elseif (stripos($driverMsg, 'Invalid datetime format') !== false) {
                    $friendly = 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.';
                } else {
                    $friendly = 'Terjadi kesalahan database saat menyimpan penjualan.';
                }
            }

            // Log full error info for debugging
            logger()->error('Tambah penjualan QueryException', ['error' => $e->getMessage(), 'errorInfo' => $errorInfo, 'payload' => $request->all()]);

            return back()->withInput()->with('error', $friendly);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Tambah penjualan gagal: ' . $e->getMessage(), [
                'payload' => $request->all()
            ]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat memproses permintaan.');
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
