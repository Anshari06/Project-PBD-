<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;


class VendorController extends Controller
{
    public function index()
    {
       $allVendors = Vendor::all();

        // tangkap query pencarian (nama) dari ?q=...
        
        return view('Vendor.vendor', compact('allVendors'));
    }
}
