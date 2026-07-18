<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        $lapangans = [
            ['nama' => 'Lapangan Futsal A', 'slug' => 'lapangan-futsal-a', 'kategori' => 'Futsal', 'harga_per_jam' => 90000, 'gambar' => 'assets/img/lapangan-futsal/lapangan-a.webp', 'rating' => 4.9],
            ['nama' => 'Lapangan Futsal B', 'slug' => 'lapangan-futsal-b', 'kategori' => 'Futsal', 'harga_per_jam' => 90000, 'gambar' => 'assets/img/lapangan-futsal/lapangan-b.webp', 'rating' => 4.8],
            ['nama' => 'Lapangan Futsal C', 'slug' => 'lapangan-futsal-c', 'kategori' => 'Futsal', 'harga_per_jam' => 90000, 'gambar' => 'assets/img/lapangan-futsal/lapangan-c.webp', 'rating' => 4.6],
            ['nama' => 'Lapangan Badminton 1', 'slug' => 'lapangan-badminton-1', 'kategori' => 'Badminton', 'harga_per_jam' => 70000, 'gambar' => 'assets/img/lapangan-badmin/badminton-a.webp', 'rating' => 5.0],
            ['nama' => 'Lapangan Badminton 2', 'slug' => 'lapangan-badminton-2', 'kategori' => 'Badminton', 'harga_per_jam' => 70000, 'gambar' => 'assets/img/lapangan-badmin/badminton-b.webp', 'rating' => 4.7],
            ['nama' => 'Lapangan Badminton 3', 'slug' => 'lapangan-badminton-3', 'kategori' => 'Badminton', 'harga_per_jam' => 70000, 'gambar' => 'assets/img/lapangan-badmin/badminton-c.webp', 'rating' => 4.8],
        ];

        foreach ($lapangans as $item) {
            Lapangan::create($item);
        }
    }
}