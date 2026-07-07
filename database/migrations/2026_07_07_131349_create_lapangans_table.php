<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lapangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_olahraga_id')->constrained('jenis_olahragas')->cascadeOnDelete();
            $table->string('nama_lapangan');
            $table->integer('harga_per_jam');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable(); // gambar utama/thumbnail
            $table->time('jam_buka')->default('06:00:00');
            $table->time('jam_tutup')->default('23:00:00');
            $table->enum('status', ['tersedia', 'perbaikan'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};
