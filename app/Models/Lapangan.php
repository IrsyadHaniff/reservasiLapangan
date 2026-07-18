<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori',
        'harga_per_jam',
        'gambar',
        'rating',
        'is_aktif',
    ];

    protected $casts = [
        'harga_per_jam' => 'integer',
        'rating'        => 'decimal:1',
        'is_aktif'      => 'boolean',
    ];

    /**
     * URL gambar lengkap, siap dipakai langsung di <img src="{{ $lapangan->gambar_url }}">
     * tanpa perlu manggil asset() lagi di Blade.
     */
    public function getGambarUrlAttribute(): string
    {
        return $this->gambar ? asset($this->gambar) : asset('assets/img/placeholder-lapangan.webp');
    }

    /**
     * Query cuma lapangan yang aktif — dipakai di halaman publik (reservasi),
     * lapangan non-aktif tetap ada di DB tapi gak ditampilkan ke pelanggan.
     * Contoh: Lapangan::aktif()->get();
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // Nanti setelah tabel reservasis dibuat:
    // public function reservasis()
    // {
    //     return $this->hasMany(Reservasi::class);
    // }
}