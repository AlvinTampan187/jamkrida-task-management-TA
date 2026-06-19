<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TugasDetail;
use App\Models\User;
use App\Models\Lampiran;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'user_id',
        'bagian',
        'kategori',
        'keterangan_laporan',
        'solusi',
        'deadline'
        // ❌ status DIHAPUS dari sini
    ];

    // ✅ Default status
    protected $attributes = [
        'status' => 'Penugasan'
    ];

    /* =========================
       RELASI
    ========================= */

    // KE DETAIL
    public function detail()
    {
        return $this->hasMany(TugasDetail::class, 'tugas_id');
    }

    // KE USER (ASSIGN)
    public function users()
    {
        return $this->belongsToMany(User::class, 'tugas_user')->withTimestamps();
    }

    // KE LAMPIRAN
    public function lampiran()
    {
        return $this->hasMany(Lampiran::class);
    }

    // KE DISKUSI
    public function diskusi()
    {
        return $this->hasMany(DiskusiTugas::class);
    }
}