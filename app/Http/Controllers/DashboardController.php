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
    $usersCount = Schema::hasTable('user') ? User::count() : 0;

    // Count only active barang (status = 1) if table exists
    $barangCount = Schema::hasTable('barang') ? DB::table('barang')->where('status', 1)->count() : 0;

    $vendorCount = Schema::hasTable('vendor') ? DB::table('vendor')->where('status', 1)->count() : 0;

    // Fetch latest 10 penjualan safely (only if table exists)
    $penjualanterbaru = Schema::hasTable('penjualan')
        ? DB::table('penjualan')
            ->select('idpenjualan', 'created_at', 'subtotal_nilai', 'total_nilai', 'idmargin_penjualan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
        : collect();

    return view('dashboard', compact('usersCount', 'barangCount', 'vendorCount', 'penjualanterbaru'));
    }


}
