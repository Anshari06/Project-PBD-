<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index()
    {
        $sql = 'SELECT * FROM satuan_vu';
        $satuans = DB::select($sql);

        return view('manage_satuan.manage_satuan', compact('satuans'));
    }
}
