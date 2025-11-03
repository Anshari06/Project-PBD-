<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use App\Models\User;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function index()
    {
        // eager-load user relation so we can access $pengadaan->user->username in the view
        $pengadaans = Pengadaan::all();

        // pass the variable to the view (use lowercase variable name to match blade)
        return view('Pengadaan.manage_pengadaan', compact('pengadaans'));
    }
}
