<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasDetail extends Model
{

    protected $table = 'tugas_detail';

    protected $fillable = [
        'tugas_id',
        'deskripsi',
        'status',
        'tanggal'
    ];

}