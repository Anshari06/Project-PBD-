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
            'tgl_penerimaan' => 'nullable|date',
        ]);

        $idpengadaan = (int) $data['idpengadaan'];
        $ids = $data['idbarang'];
        $jmls = $data['jumlah'];
        $iduser = Auth::id() ?? $request->input('iduser');

        // array length check
        if (count($ids) !== count($jmls)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah barang dan jumlah terima tidak sesuai.');
        }

        // ambil data detail_pengadaan langsung dari tabel (bukan dari view)
        $detailRows = DB::table('detail_pengadaan as d')
            ->leftJoin('barang as b', 'd.idbarang', '=', 'b.idbarang')
            ->where('d.idpengadaan', $idpengadaan)
            ->whereIn('d.idbarang', $ids)
            ->select('d.idbarang', 'd.jumlah as jumlah_dipesan', DB::raw('IFNULL((
            SELECT SUM(dp.jumlah_terima) FROM detail_penerimaan dp
            JOIN penerimaan p2 ON p2.idpenerimaan = dp.idpenerimaan
            WHERE p2.idpengadaan = d.idpengadaan AND dp.idbarang = d.idbarang
        ),0) as jumlah_diterima'))
            ->get()
            ->keyBy('idbarang');

        $errors = [];
        foreach ($ids as $i => $bid) {
            $bid = (int) $bid;
            $reqQty = (int) ($jmls[$i] ?? 0);
            $row = $detailRows->get($bid);

            $ordered = $row ? (int)$row->jumlah_dipesan : 0;
            $received = $row ? (int)$row->jumlah_diterima : 0;
            $sisa = max(0, $ordered - $received);

            if ($ordered === 0) {
                $errors[] = "Barang ID {$bid} tidak ditemukan di pengadaan.";
                continue;
            }

            if ($reqQty > $sisa) {
                $errors[] = "Permintaan untuk barang ID {$bid} melebihi sisa ({$sisa}). DEEEMN LU GA FINESHEEYT BRUH";
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $errors));
        }

        try {
            // panggil SP per item (SP Tambah_penerimaan menangani 1 barang)
            DB::beginTransaction();
            for ($i = 0; $i < count($ids); $i++) {
                $bid = (int)$ids[$i];
                $qty = (int)$jmls[$i];

                // log lebih mudah debugging
                logger()->info('Calling Tambah_penerimaan item', [
                    'idpengadaan' => $idpengadaan,
                    'idbarang' => $bid,
                    'qty' => $qty,
                    'iduser' => $iduser
                ]);

                // SP signature: Tambah_penerimaan(p_idpengadaan INT, p_idbarang INT, p_jumlah_terima INT, p_iduser INT)
                DB::select('CALL Tambah_penerimaan(?, ?, ?, ?)', [
                    $idpengadaan,
                    $bid,
                    $qty,
                    $iduser,
                ]);
            }
            DB::commit();

            return redirect('/manage_penerimaan')->with('success', 'Penerimaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Tambah penerimaan failed: ' . $e->getMessage(), [
                'payload' => $request->all()
            ]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambah penerimaan: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $penerimaan = DB::table('pengadaan_penerimaan')
            ->where('idpenerimaan', $id)
            ->first();

        $detailPenerimaans = DB::table('pengadaan_penerimaan')
            ->where('idpenerimaan', $id)
            ->get();

        return view('penerimaan.detail_penerimaan', compact('penerimaan', 'detailPenerimaans'));
    }

    /**
     * Return items and total for a given pengadaan (used by AJAX requests).
     */
    public function getPengadaanItems($id)
    {
        $rows = DB::table('pengadaan_barang')
            ->where('idpengadaan', $id)
            ->get();

        $items = [];
        foreach ($rows as $r) {
            // try to read common column names used across versions
            $jumlah = isset($r->jumlah) ? (float) $r->jumlah : (isset($r->jumlah_barang) ? (float) $r->jumlah_barang : 0);
            $total_diterima = isset($r->total_diterima) ? (float) $r->total_diterima : (isset($r->diterima) ? (float) $r->diterima : 0);
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
}
