<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::all();

        return view('Penjualan.manage_penjualan', compact('penjualans'));
    }
}
