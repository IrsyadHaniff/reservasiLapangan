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

    /**
     * Cek bentrok jadwal pakai DATETIME penuh (tanggal+jam), bukan jam doang.
     * Ini yang bikin perbandingan otomatis benar walau reservasinya nyebrang
     * tengah malam (misal 23:00 - 01:00 besok paginya).
     */
    public function isTersediaPada(\Carbon\Carbon $mulaiAt, \Carbon\Carbon $selesaiAt): bool
    {
        $bentrok = $this->reservasis()
            ->aktif()
            ->where('mulai_at', '<', $selesaiAt)
            ->where('selesai_at', '>', $mulaiAt)
            ->exists();

        return ! $bentrok;
    }

    /**
     * Jendela operasional per "hari booking": 06:00 tanggal itu s/d 05:00
     * KEESOKAN harinya (23 slot jam, tutup jam 05:00-06:00). Dipakai buat
     * generate grid pemilihan jam di halaman booking, dan buat cek "penuh".
     */
    public static function jendelaOperasional(string $tanggal): array
    {
        $mulai  = \Carbon\Carbon::parse($tanggal)->setTime(6, 0);
        $selesai = $mulai->copy()->addHours(23); // 06:00 - 05:00 besok = 23 jam

        return [$mulai, $selesai];
    }

    /**
     * Ambil index slot (0-22) mana aja yang sudah kepakai reservasi aktif
     * di jendela operasional tanggal ini. Dipakai di halaman booking (grid
     * jam) dan admin (jadwal).
     */
    public function slotTerpakaiPada(string $tanggal): array
    {
        [$windowMulai, $windowSelesai] = self::jendelaOperasional($tanggal);

        $indexes = [];

        $this->reservasis()
            ->aktif()
            ->where('mulai_at', '<', $windowSelesai)
            ->where('selesai_at', '>', $windowMulai)
            ->get(['mulai_at', 'selesai_at'])
            ->each(function ($reservasi) use ($windowMulai, $windowSelesai, &$indexes) {
                $awal  = $reservasi->mulai_at->max($windowMulai);
                $akhir = $reservasi->selesai_at->min($windowSelesai);

                for ($jam = $awal->copy(); $jam->lt($akhir); $jam->addHour()) {
                    $indexes[] = $windowMulai->diffInHours($jam);
                }
            });

        return array_values(array_unique($indexes));
    }

    public function isPenuhPada(string $tanggal): bool
    {
        return count($this->slotTerpakaiPada($tanggal)) >= 23; // 23 slot jam per hari (06:00-24:00 + 00:00-05:00)
    }

    public function isPenuhHariIni(): bool
    {
        // Kalau jam sekarang masih dini hari (00:00-05:59), itu masih bagian
        // dari "hari operasional" kemarin (yang belum tutup), bukan hari ini.
        $tanggalOperasional = now()->hour < 6
            ? now()->subDay()->toDateString()
            : now()->toDateString();

        return $this->isPenuhPada($tanggalOperasional);
    }
}