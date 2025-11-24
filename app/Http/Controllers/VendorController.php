<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        // allow toggling between active-only (default) and all vendors via ?show_all=1
        $showAll = $request->boolean('show_all');

        if ($showAll) {
            $allVendors = DB::table('vendor')->get();
        } else {
            $allVendors = DB::table('vendor_aktif')->get();
        }

        $vendorStatus = null;

        if ($request->has('vendor_id')) {
            $idvendor = $request->input('vendor_id');
            $result = DB::select('SELECT cekStatusLengkapVendor(?) as Status', [$idvendor]);

            // ambil hasil pertama (karena DB::select() balikin array of object)
            $vendorStatus = $result[0]->Status ?? 'Tidak ditemukan';
        }

        return view('Vendor.vendor', compact('allVendors', 'vendorStatus', 'showAll'));
    }
}
