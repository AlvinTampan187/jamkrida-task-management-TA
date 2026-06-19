<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    protected $table = 'lampiran';

    protected $fillable = [
        'tugas_id',
        'nama_file',
        'file_path',
        'keterangan'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
