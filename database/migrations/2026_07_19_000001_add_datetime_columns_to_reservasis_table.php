<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // Ini yang menggantikan jam_mulai/jam_selesai (TIME doang) sebagai sumber
            // kebenaran buat cek bentrok jadwal. TIME doang gak punya info "tanggal",
            // jadi matematika jam yang nyebrang tengah malam (23:00 -> 01:00) jadi
            // kacau. DATETIME menyimpan tanggal+jam sekaligus, jadi perbandingan
            // "sebelum/sesudah" otomatis benar walau nyebrang hari.
            $table->dateTime('mulai_at')->nullable()->after('jam_selesai');
            $table->dateTime('selesai_at')->nullable()->after('mulai_at');
        });

        // Backfill data yang sudah ada. Kalau jam_selesai <= jam_mulai (berarti
        // nyebrang tengah malam), tambahkan 1 hari ke selesai_at.
        DB::table('reservasis')->orderBy('id')->get()->each(function ($reservasi) {
            $mulaiAt = \Illuminate\Support\Carbon::parse($reservasi->tanggal . ' ' . $reservasi->jam_mulai);
            $selesaiAt = \Illuminate\Support\Carbon::parse($reservasi->tanggal . ' ' . $reservasi->jam_selesai);

            if ($selesaiAt->lessThanOrEqualTo($mulaiAt)) {
                $selesaiAt->addDay();
            }

            DB::table('reservasis')->where('id', $reservasi->id)->update([
                'mulai_at'   => $mulaiAt,
                'selesai_at' => $selesaiAt,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn(['mulai_at', 'selesai_at']);
        });
    }
};