<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailPenerimaanController extends Controller
{
    public function index()
    {
        return view('penerimaan.detail_penerimaan');
    }
}
