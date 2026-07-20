<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservasi extends Model
{
    use HasFactory;

    const STATUS_MENUNGGU     = 'menunggu_verifikasi';
    const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    const STATUS_DITOLAK      = 'ditolak';
    const STATUS_SELESAI      = 'selesai';
    const STATUS_DIBATALKAN   = 'dibatalkan';

    protected $fillable = [
        'nomor_pesanan',
        'lapangan_id',
        'nama_lapangan',
        'user_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'mulai_at',
        'selesai_at',
        'durasi_jam',
        'harga_per_jam',
        'total_harga',
        'metode_pembayaran',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal'       => 'date',
        'mulai_at'      => 'datetime',
        'selesai_at'    => 'datetime',
        'durasi_jam'    => 'integer',
        'harga_per_jam' => 'integer',
        'total_harga'   => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // Nomor pesanan digenerate otomatis kalau belum diisi manual,
        // jadi controller tinggal Reservasi::create([...]) tanpa mikirin nomor.
        static::creating(function (Reservasi $reservasi) {
            if (empty($reservasi->nomor_pesanan)) {
                $reservasi->nomor_pesanan = self::generateNomorPesanan();
            }
        });
    }

    public static function generateNomorPesanan(): string
    {
        do {
            $nomor = 'SMSPORT-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        } while (self::where('nomor_pesanan', $nomor)->exists());

        return $nomor;
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAktif($query)
    {
        // "Aktif" = masih menghalangi slot jam itu dipesan orang lain
        return $query->whereIn('status', [self::STATUS_MENUNGGU, self::STATUS_DIKONFIRMASI]);
    }
}