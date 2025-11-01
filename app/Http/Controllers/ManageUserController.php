<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ManageUserController extends Controller
{
    public function index()
    {
        $sql = 'SELECT * FROM user_role_vu ORDER BY iduser';
        $users = DB::select($sql);

        // so reference it as 'manage_user.manage_user'
        return view('manage_user.manage_user', compact('users'));
    }
    
}
