<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_detail', function (Blueprint $table) {

            $table->id();

            // RELASI KE TABEL TUGAS
            $table->foreignId('tugas_id')
                  ->constrained('tugas')
                  ->onDelete('cascade');

            $table->string('deskripsi');

            $table->integer('estimasi')->nullable();

            $table->enum('status',['Penugasan','Progres','Selesai'])->default('Penugasan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_detail');
    }
};