<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginController extends Controller
{
    /**
     * Display a listing of margins.
     */
    public function index()
    {
        $margins = DB::table('margin_penjualan_aktif')->get();

        return view('margin.manage_margin', compact('margins'));
    }
}
