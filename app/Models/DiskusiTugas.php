<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskusiTugas extends Model
{

    protected $table = 'diskusi_tugas';


    protected $fillable = [
        'tugas_id',
        'user_id',
        'pesan'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

}