<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
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

    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate slug dari nama kalau gak diisi manual saat create.
        // Jadi controller/seeder tinggal Lapangan::create(['nama' => '...', ...])
        // tanpa perlu mikirin slug-nya.
        static::creating(function (Lapangan $lapangan) {
            if (empty($lapangan->slug)) {
                $lapangan->slug = self::generateUniqueSlug($lapangan->nama);
            }
        });

        // Kalau nama diubah lewat dashboard admin nanti dan slug belum di-custom manual,
        // regenerate juga slug-nya biar tetap nyambung sama nama terbaru.
        static::updating(function (Lapangan $lapangan) {
            if ($lapangan->isDirty('nama') && ! $lapangan->isDirty('slug')) {
                $lapangan->slug = self::generateUniqueSlug($lapangan->nama, $lapangan->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $base = Str::slug($nama);
        $slug = $base;
        $i = 1;

        while (
            self::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Ini yang bikin route model binding pakai slug, bukan id.
     * Jadi Route::get('/{lapangan}', ...) otomatis nyari berdasarkan kolom `slug`,
     * dan route('booking.show', $lapangan) otomatis generate URL pakai slug juga.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getGambarUrlAttribute(): string
    {
        if (! $this->gambar) {
            return asset('assets/img/placeholder-lapangan.webp');
        }

        // Data lama dari seeder pakai path assets/img/... (public folder langsung).
        // Upload baru dari dashboard admin pakai Storage disk 'public' (storage/app/public/lapangan/...).
        if (str_starts_with($this->gambar, 'assets/')) {
            return asset($this->gambar);
        }

        return asset('storage/' . $this->gambar);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function isTersediaPada(string $tanggal, string $jamMulai, string $jamSelesai): bool
    {
        $bentrok = $this->reservasis()
            ->aktif()
            ->where('tanggal', $tanggal)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->exists();

        return ! $bentrok;
    }

    /**
     * Cek apakah SEMUA slot jam operasional (06:00-24:00, 18 slot) di tanggal
     * tertentu sudah kepakai reservasi aktif. Dipakai buat badge "Penuh" di
     * card reservasi — beda dari isTersediaPada() yang cek rentang jam spesifik.
     */
    public function isPenuhPada(string $tanggal): bool
    {
        $slotTerpakai = [];

        $this->reservasis()
            ->aktif()
            ->where('tanggal', $tanggal)
            ->get(['jam_mulai', 'jam_selesai'])
            ->each(function ($reservasi) use (&$slotTerpakai) {
                $awal  = (int) \Illuminate\Support\Carbon::parse($reservasi->jam_mulai)->format('H');
                $akhir = (int) \Illuminate\Support\Carbon::parse($reservasi->jam_selesai)->format('H');
                for ($h = $awal; $h < $akhir; $h++) {
                    $slotTerpakai[$h] = true;
                }
            });

        return count($slotTerpakai) >= 18; // 06:00 - 24:00 = 18 slot jam
    }

    public function isPenuhHariIni(): bool
    {
        return $this->isPenuhPada(now()->toDateString());
    }
}