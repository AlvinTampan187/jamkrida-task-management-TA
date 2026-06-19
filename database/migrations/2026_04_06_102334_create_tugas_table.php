<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {

            $table->id();

            // FOREIGN KEY KE USERS
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('bagian');

            $table->string('kategori');

            $table->text('keterangan_laporan');

            $table->text('solusi')->nullable();

            $table->string('foto')->nullable();

            $table->enum('status',['Penugasan','Progres','Selesai'])->default('Penugasan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};