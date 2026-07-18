<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nama');
        });

        // Backfill slug untuk data yang sudah ada, biar gak perlu migrate:fresh
        DB::table('lapangans')->orderBy('id')->get()->each(function ($lapangan) {
            DB::table('lapangans')->where('id', $lapangan->id)->update([
                'slug' => Str::slug($lapangan->nama) . '-' . $lapangan->id,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};