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
            $table->string('nama_lapangan')->nullable()->after('lapangan_id');
        });

        // Backfill nama_lapangan dari relasi lapangan yang ada sekarang,
        // buat data reservasi yang sudah kepencet sebelum kolom ini ada
        DB::table('reservasis')->orderBy('id')->get()->each(function ($reservasi) {
            $lapangan = DB::table('lapangans')->find($reservasi->lapangan_id);
            DB::table('reservasis')->where('id', $reservasi->id)->update([
                'nama_lapangan' => $lapangan->nama ?? '-',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn('nama_lapangan');
        });
    }
};