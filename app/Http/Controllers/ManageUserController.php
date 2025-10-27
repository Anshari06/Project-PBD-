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
        if (Schema::hasTable('user_role_vu')) {
            $sql = 'SELECT * FROM user_role_vu ORDER BY iduser DESC';
            $users = DB::select($sql);
        } elseif (Schema::hasTable('user')) {
            // Fallback to the 'user' table (alias columns to match view expectations)
                // The actual columns in your `user` table are `iduser`, `username`, `password`, `idrole`.
                // Use those names — don't assume `id`/`name` which caused the SQL error.
                // We'll select iduser/username/password and map idrole -> role (numeric) as a safe default.
                $sql = "SELECT iduser, username, password, idrole AS role FROM `user` ORDER BY iduser DESC";
            $users = DB::select($sql);
        } else {
            $users = collect();
        }

        return view('manage_user', compact('users'));
    }
}
