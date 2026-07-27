<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tugas_detail', function (Blueprint $table) {
            $table->enum('status', [
                'Penugasan',
                'Progres',
                'Menunggu Persetujuan',
                'Selesai'
            ])->default('Penugasan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
