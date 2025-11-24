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
        $pengadaans = DB::table('pengadaan')
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
            'idbarang'    => 'required|array|min:1',
            'idbarang.*'  => 'required|integer|exists:barang,idbarang',
            'jumlah'      => 'required|array|min:1',
            'jumlah.*'    => 'required|integer|min:1',
        ]);

        $idpengadaan = (int)$data['idpengadaan'];
        $ids  = $data['idbarang'];
        $qtys = $data['jumlah'];
        $iduser = Auth::id();

        if (count($ids) !== count($qtys)) {
            return back()->with('error', 'Jumlah baris barang tidak sesuai.');
        }

        try {
            DB::beginTransaction();

            $idpenerimaan = null; // akan diisi setelah pemanggilan pertama

            foreach ($ids as $i => $idb) {

                // CALL SP
                $result = DB::select(
                    'CALL Tambah_penerimaan(?, ?, ?, ?, ?)',
                    [
                        $idpenerimaan,      // NULL pada loop pertama → buat header penerimaan
                        $idpengadaan,
                        (int)$idb,
                        (int)$qtys[$i],
                        $iduser
                    ]
                );

                // Ambil idpenerimaan dari SP jika masih null
                if ($idpenerimaan === null) {
                    $idpenerimaan = $result[0]->idpenerimaan;
                }
            }

            DB::commit();

            return redirect('/manage_penerimaan')
                ->with('success', 'Penerimaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }



    public function show($id)
    {
        $penerimaan = DB::table('pengadaan_penerimaan')
            ->where('idpenerimaan', $id)
            ->first();

        $detailPenerimaans = DB::table('penerimaan_barang')
            ->where('idpenerimaan', $id)
            ->get();

        return view('penerimaan.detail_penerimaan', compact('penerimaan', 'detailPenerimaans'));
    }

    /**
     * Return items and total for a given pengadaan (used by AJAX requests).
     */
    public function getPengadaanItems($id)
    {
        // Fetch pengadaan items and compute already received quantity per item
        $rows = DB::table('pengadaan_barang as pb')
            ->where('pb.idpengadaan', $id)
            ->select([
                'pb.*',
                DB::raw('(SELECT IFNULL(SUM(dp.jumlah_terima),0) FROM detail_penerimaan dp JOIN penerimaan p2 ON p2.idpenerimaan = dp.idpenerimaan WHERE p2.idpengadaan = pb.idpengadaan AND dp.idbarang = pb.idbarang) as total_diterima')
            ])
            ->get();

        $items = [];
        foreach ($rows as $r) {
            // try to read common column names used across versions
            $jumlah = isset($r->jumlah) ? (float) $r->jumlah : (isset($r->jumlah_barang) ? (float) $r->jumlah_barang : 0);
            $total_diterima = isset($r->total_diterima) ? (float) $r->total_diterima : 0;
            $harga = isset($r->harga) ? (float) $r->harga : (isset($r->harga_satuan) ? (float) $r->harga_satuan : (isset($r->price) ? (float) $r->price : 0));
            $subtotal = $harga * $jumlah;
            $sisa = max(0, $jumlah - $total_diterima);

            $items[] = [
                'idbarang' => $r->idbarang ?? null,
                'nama' => $r->nama ?? ($r->nama_barang ?? null),
                'harga' => $harga,
                'jumlah' => $jumlah,
                'total_diterima' => $total_diterima,
                'sisa' => $sisa,
                'subtotal' => $subtotal,
            ];
        }

        // total may be stored on rows or pengadaan header — try to extract safely
        $total = 0;
        if ($rows->isNotEmpty()) {
            $first = $rows->first();
            if (isset($first->total_nilai)) $total = (float) $first->total_nilai;
            elseif (isset($first->total)) $total = (float) $first->total;
        }

        return response()->json(['total' => $total, 'items' => $items]);
    }

    public function destroy($id)
    {
        try {
            DB::delete("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$id]);

            return redirect('/manage_penerimaan')->with('success', 'Penerimaan berhasil dihapus.');
        } catch (\Exception $e) {
            logger()->error('Hapus penerimaan failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus penerimaan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified penerimaan (used to set status).
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status_penerimaan' => 'required|string|in:O,S,R',
        ]);

        try {
            $affected = DB::update('UPDATE pengadaan_penerimaan SET status_penerimaan = ? WHERE idpenerimaan = ?', [
                $data['status_penerimaan'],
                $id,
            ]);

            if ($affected) {
                return redirect('/manage_penerimaan')->with('success', 'Status penerimaan berhasil diperbarui.');
            }

            return redirect('/manage_penerimaan')->with('error', 'Penerimaan tidak ditemukan atau tidak diubah.');
        } catch (\Exception $e) {
            logger()->error('Update penerimaan status failed: ' . $e->getMessage(), ['id' => $id, 'payload' => $request->all()]);
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}
