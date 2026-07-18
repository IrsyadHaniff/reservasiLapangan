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
            $table->string('nama');
            $table->enum('kategori', ['Futsal', 'Badminton']);
            $table->unsignedInteger('harga_per_jam');
            $table->string('gambar')->nullable(); // path relatif, contoh: assets/img/lapangan-futsal-a.webp
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->boolean('is_aktif')->default(true); // buat admin "nonaktifkan" lapangan tanpa hapus datanya
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};
