<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function karyawan()
    {
        $users = User::where('role','karyawan')
            ->select('id','name','bagian') // 🔥 tambahin ini
            ->get();

        return response()->json($users);
    }
    public function getBagian()
    {
        return User::select('bagian')
            ->distinct()
            ->pluck('bagian');
    }
}