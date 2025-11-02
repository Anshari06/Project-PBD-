<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detail_Penjualan;

class DetailPenjualanController extends Controller
{
    public function index()
    {
        $detailPenjualans = Detail_Penjualan::all();
        return view('penjualan.detail_penjualan', compact('detailPenjualans'));
    }
}
