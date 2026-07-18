<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan')->unique(); // contoh: SMSPORT-260718-AB3KD, di-generate otomatis di model

            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // selalu terisi, karena akun auto-dibuat walau pelanggan gak daftar manual

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedTinyInteger('durasi_jam');

            // Snapshot harga saat booking — biar histori gak berubah walau
            // admin ganti harga lapangan di kemudian hari
            $table->unsignedInteger('harga_per_jam');
            $table->unsignedInteger('total_harga');

            $table->enum('metode_pembayaran', ['qris', 'va_bca']);
            $table->enum('status', [
                'menunggu_verifikasi',
                'dikonfirmasi',
                'ditolak',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi');

            $table->text('catatan_admin')->nullable(); // alasan ditolak, catatan verifikasi, dll

            $table->timestamps();

            // Index buat query "cek slot bentrok di tanggal X" & "jadwal lapangan Y"
            $table->index(['lapangan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};