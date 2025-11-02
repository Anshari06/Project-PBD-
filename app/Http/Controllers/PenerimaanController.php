<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use Illuminate\Http\Request;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $penerimaans= Penerimaan::all();

        return view('penerimaan.manage_penerimaan', compact('penerimaans'));
    }
}
