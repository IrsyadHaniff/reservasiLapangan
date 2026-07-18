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
        return $this->gambar ? asset($this->gambar) : asset('assets/img/placeholder-lapangan.webp');
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

    public function isTersediaSekarang(): bool
    {
        return $this->isTersediaPada(
            now()->toDateString(),
            now()->format('H:i:s'),
            now()->format('H:i:s')
        );
    }
}