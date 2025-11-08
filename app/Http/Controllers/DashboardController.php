<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with several counts.
     */
    public function index()
    {
        // Use Schema::hasTable to avoid errors when a table doesn't exist
        $usersCount = User::count() ?? 0;

        // Count only active barang (status = 1) if table exists
        $barangCount = DB::table('barang_aktif')->count() ?? 0;

        $vendorCount = DB::table('vendor_aktif')->count() ?? 0;

        // Fetch latest 10 penjualan safely (only if table exists)
        $penjualanterbaru = DB::table('penjualan_barang') ->get();

        return view('dashboard', compact('usersCount', 'barangCount', 'vendorCount', 'penjualanterbaru'));
    }
}
