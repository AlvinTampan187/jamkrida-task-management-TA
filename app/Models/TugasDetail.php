<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasDetail extends Model
{
    protected $table = 'tugas_detail';

    protected $fillable = [
        'tugas_id',
        'deskripsi',
        'estimasi',
        'status',
        'user_id',
        'tanggal'
    ];

    /* =========================
       RELASI KE TUGAS
    ========================= */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    /* =========================
       RELASI KE USER (PEMBUAT)
    ========================= */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
