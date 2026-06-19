<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;

class TugasController extends Controller
{
    public function store(Request $request)
    {

        $tugas = Tugas::create([
            'user_id' => $request->user_id,
            'bagian' => $request->bagian,
            'kategori' => $request->kategori,
            'keterangan_laporan' => $request->keterangan_laporan,
            'solusi' => $request->solusi
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $tugas
        ]);
    }
}