@extends('layouts.app')

@section('title', 'Booking Lapangan - SM-SPORT CENTER')

@section('content')

@php
    // $lapangan sekarang dikirim dari BookingController via route model binding — sudah bukan dummy.

    // Jam operasional 06.00 - 24.00, per slot 1 jam
    $slots = [];
    for ($h = 6; $h < 24; $h++) {
        $slots[] = ['label' => sprintf('%02d:00', $h), 'end' => sprintf('%02d:00', $h + 1)];
    }

    // Dummy slot yang sudah dibooking (index array $slots, bukan jam asli)
    // index 0 = 06:00, jadi index 4 = 10:00, dst.
    $bookedIndexes = [2, 3, 8, 9, 10, 15];
@endphp

<div
    x-data="{
        nama: '',
        email: '',
        hp: '',
        tanggal: '{{ now()->format('Y-m-d') }}',
        slots: @js($slots),
        booked: @js($bookedIndexes),
        hargaPerJam: {{ $lapangan->harga_per_jam }},
        selectedStart: null,
        duration: 1,
        maxDurasi: 6,
        metode: '',
        vaNumber: '',
        submitted: false,
        orderNumber: '',

        get selectedIndexes() {
            if (this.selectedStart === null) return [];
            return Array.from({ length: this.duration }, (_, i) => this.selectedStart + i);
        },
        get total() {
            return this.duration * this.hargaPerJam;
        },
        get canIncreaseDuration() {
            if (this.selectedStart === null) return false;
            const next = this.selectedStart + this.duration;
            return this.duration < this.maxDurasi && next < this.slots.length && !this.booked.includes(next);
        },
        get jamMulaiLabel() {
            return this.selectedStart !== null ? this.slots[this.selectedStart].label : '-';
        },
        get jamSelesaiLabel() {
            if (this.selectedStart === null) return '-';
            const endIdx = this.selectedStart + this.duration - 1;
            return this.slots[endIdx].end;
        },
        isBooked(i) { return this.booked.includes(i); },
        isSelected(i) { return this.selectedIndexes.includes(i); },
        selectSlot(i) {
            if (this.isBooked(i)) return;
            this.selectedStart = i;
            this.duration = 1;
        },
        increaseDuration() { if (this.canIncreaseDuration) this.duration++; },
        decreaseDuration() { if (this.duration > 1) this.duration--; },
        onMetodeChange() {
            if (this.metode === 'va_bca' && !this.vaNumber) {
                this.vaNumber = '7001' + Math.floor(100000000 + Math.random() * 900000000);
            }
        },
        isFormValid() {
            return this.nama.trim() && this.email.trim() && this.hp.trim() && this.selectedStart !== null && this.metode;
        },
        formatRupiah(n) {
            return 'Rp' + n.toLocaleString('id-ID');
        },
        confirmPayment() {
            if (!this.isFormValid()) return;
            const rand = Math.floor(1000 + Math.random() * 9000);
            this.orderNumber = 'SMSPORT-' + Date.now().toString().slice(-6) + rand;
            this.submitted = true;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        copyText(text) {
            navigator.clipboard?.writeText(text);
        }
    }"
    class="bg-white min-h-screen pt-16 md:pt-20"
>
    {{-- ================= FORM BOOKING ================= --}}
    <template x-if="!submitted">
        <div>
            {{-- Header banner --}}
            <div class="relative h-56 md:h-72 overflow-hidden bg-gray-100">
                <img
                    src="{{ $lapangan->gambar_url }}"
                    alt="{{ $lapangan->nama }}"
                    width="1600" height="500"
                    loading="eager"
                    fetchpriority="high"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-brand-black/80 via-brand-black/20 to-transparent"></div>
                <div class="absolute inset-0 flex items-end">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 w-full">
                        <nav aria-label="Breadcrumb" class="text-xs text-gray-200 mb-2">
                            <a href="{{ url('/#top') }}" class="hover:text-brand">Home</a>
                            <span class="mx-1.5">/</span>
                            <a href="{{ url('/#reservasi') }}" class="hover:text-brand">Reservasi</a>
                            <span class="mx-1.5">/</span>
                            <span class="text-white">{{ $lapangan->nama }}</span>
                        </nav>
                        <div class="flex items-end justify-between gap-4 flex-wrap">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full bg-brand/20 text-brand text-xs font-semibold mb-2">
                                    {{ $lapangan->kategori }}
                                </span>
                                <h1 class="font-display font-bold text-2xl md:text-4xl text-white">{{ $lapangan->nama }}</h1>
                            </div>
                            <div class="flex items-center gap-1 text-white text-sm bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-full">
                                <svg class="w-4 h-4 text-brand" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 0 0 .951.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 0 0-.363 1.118l1.287 3.958c.3.922-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 0 0-1.176 0l-3.367 2.447c-.783.57-1.838-.196-1.538-1.118l1.287-3.958a1 1 0 0 0-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 0 0 .95-.69l1.287-3.958Z" />
                                </svg>
                                {{ number_format($lapangan->rating, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
                <div class="grid lg:grid-cols-3 gap-8 lg:gap-10 items-start">

                    {{-- Kolom Form --}}
                    <div class="lg:col-span-2 space-y-8">

                        {{-- Step 1: Data Pelanggan --}}
                        <div class="border border-gray-200 rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand text-brand-black text-sm font-bold">1</span>
                                <h2 class="font-display font-bold text-lg text-brand-black">Data Pelanggan</h2>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <input id="nama" type="text" x-model="nama" placeholder="Nama sesuai identitas"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                    <input id="email" type="email" x-model="email" placeholder="nama@email.com"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                                </div>
                                <div>
                                    <label for="hp" class="block text-sm font-medium text-gray-700 mb-1.5">No. HP / WhatsApp</label>
                                    <input id="hp" type="tel" x-model="hp" placeholder="08xxxxxxxxxx"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-4 flex items-start gap-1.5">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                </svg>
                                Belum punya akun? Tidak masalah — akun otomatis dibuatkan setelah booking berhasil.
                            </p>
                        </div>

                        {{-- Step 2: Pilih Jadwal --}}
                        <div class="border border-gray-200 rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand text-brand-black text-sm font-bold">2</span>
                                <h2 class="font-display font-bold text-lg text-brand-black">Pilih Tanggal & Jam</h2>
                            </div>

                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Main</label>
                            <input id="tanggal" type="date" x-model="tanggal" min="{{ now()->format('Y-m-d') }}"
                                   class="w-full sm:w-64 px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand mb-5">

                            {{-- Legenda --}}
                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded border border-gray-300 bg-white inline-block"></span> Tersedia</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-brand inline-block"></span> Dipilih</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-court-booked/20 border border-court-booked/40 inline-block"></span> Terisi</span>
                            </div>

                            {{-- Grid jam, seperti pilih kursi bioskop --}}
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                                <template x-for="(slot, index) in slots" :key="index">
                                    <button
                                        type="button"
                                        @click="selectSlot(index)"
                                        :disabled="isBooked(index)"
                                        :class="{
                                            'bg-brand text-brand-black border-brand font-semibold': isSelected(index),
                                            'bg-court-booked/10 text-court-booked/70 border-court-booked/30 cursor-not-allowed line-through': isBooked(index),
                                            'bg-white text-gray-600 border-gray-300 hover:border-brand hover:text-brand-dark': !isSelected(index) && !isBooked(index)
                                        }"
                                        class="px-2 py-2.5 rounded-lg border text-xs sm:text-sm text-center transition-colors duration-150"
                                        x-text="slot.label"
                                    ></button>
                                </template>
                            </div>

                            <p class="text-xs text-gray-400 mt-4" x-show="selectedStart === null">
                                Klik salah satu jam untuk mulai memilih.
                            </p>
                        </div>

                        {{-- Step 3: Durasi Main --}}
                        <div class="border border-gray-200 rounded-2xl p-5 md:p-6" :class="selectedStart === null ? 'opacity-50 pointer-events-none' : ''">
                            <div class="flex items-center gap-2 mb-5">
                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand text-brand-black text-sm font-bold">3</span>
                                <h2 class="font-display font-bold text-lg text-brand-black">Durasi Main</h2>
                            </div>
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center gap-4">
                                    <button type="button" @click="decreaseDuration()" :disabled="duration <= 1"
                                            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-300 text-gray-600 hover:border-brand hover:text-brand-dark disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                                        <span class="text-lg font-bold leading-none">−</span>
                                    </button>
                                    <span class="text-xl font-display font-bold text-brand-black w-20 text-center" x-text="duration + ' Jam'"></span>
                                    <button type="button" @click="increaseDuration()" :disabled="!canIncreaseDuration"
                                            class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-300 text-gray-600 hover:border-brand hover:text-brand-dark disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                                        <span class="text-lg font-bold leading-none">+</span>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Jam bermain: <span class="font-semibold text-brand-black" x-text="jamMulaiLabel + ' - ' + jamSelesaiLabel"></span>
                                </p>
                            </div>
                            <p class="text-xs text-gray-400 mt-3" x-show="!canIncreaseDuration && selectedStart !== null">
                                Durasi tidak bisa ditambah — jam berikutnya sudah terisi atau di luar jam operasional.
                            </p>
                        </div>

                        {{-- Step 4: Pembayaran --}}
                        <div class="border border-gray-200 rounded-2xl p-5 md:p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-brand text-brand-black text-sm font-bold">4</span>
                                <h2 class="font-display font-bold text-lg text-brand-black">Metode Pembayaran</h2>
                            </div>

                            <label for="metode" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih metode</label>
                            <select id="metode" x-model="metode" @change="onMetodeChange()"
                                    class="w-full sm:w-72 px-4 py-2.5 rounded-xl border border-gray-300 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand cursor-pointer">
                                <option value="" disabled>Pilih metode pembayaran</option>
                                <option value="qris">QRIS</option>
                                <option value="va_bca">Virtual Account BCA</option>
                            </select>

                            {{-- Preview QRIS --}}
                            <div x-show="metode === 'qris'" x-cloak class="mt-5 flex flex-col items-center gap-3 p-5 bg-gray-50 rounded-xl border border-gray-200 max-w-xs">
                                <div class="w-40 h-40 bg-white border border-gray-300 rounded-lg flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h4.5v4.5h-4.5v-4.5Zm0 10.5h4.5v4.5h-4.5v-4.5Zm10.5-10.5h4.5v4.5h-4.5v-4.5Zm0 6h4.5m-4.5 4.5h1.5m3 0h-1.5m0-4.5v4.5m-6-9h1.5" />
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-500 text-center">Scan QR untuk membayar (simulasi, bukan transaksi asli)</p>
                            </div>

                            {{-- Preview VA BCA --}}
                            <div x-show="metode === 'va_bca'" x-cloak class="mt-5 p-5 bg-gray-50 rounded-xl border border-gray-200 max-w-sm">
                                <p class="text-xs text-gray-500 mb-1">Nomor Virtual Account BCA</p>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-mono font-semibold text-brand-black text-lg tracking-wide" x-text="vaNumber"></span>
                                    <button type="button" @click="copyText(vaNumber)" class="text-xs text-brand-dark font-medium hover:underline shrink-0">Salin</button>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Simulasi — tidak ada transaksi asli yang diproses.</p>
                            </div>

                            <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mt-5 inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12V16.5Zm9-4.5a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Ini simulasi pembayaran, tidak ada transaksi nyata yang diproses.
                            </p>
                        </div>
                    </div>

                    {{-- Sidebar Ringkasan --}}
                    <aside class="lg:sticky lg:top-24">
                        <div class="border border-gray-200 rounded-2xl p-5 md:p-6 bg-white shadow-sm">
                            <h2 class="font-display font-bold text-lg text-brand-black mb-4">Ringkasan Booking</h2>
                            <div class="flex gap-3 pb-4 border-b border-gray-100">
                                <img src="{{ $lapangan->gambar_url }}" alt="" width="64" height="64" loading="lazy" class="w-16 h-16 rounded-lg object-cover shrink-0">
                                <div>
                                    <p class="font-semibold text-brand-black text-sm">{{ $lapangan->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $lapangan->kategori }} · Indoor</p>
                                </div>
                            </div>

                            <dl class="space-y-2.5 py-4 border-b border-gray-100 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Tanggal</dt>
                                    <dd class="text-brand-black font-medium" x-text="tanggal"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Jam</dt>
                                    <dd class="text-brand-black font-medium" x-text="selectedStart === null ? '-' : (jamMulaiLabel + ' - ' + jamSelesaiLabel)"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Durasi</dt>
                                    <dd class="text-brand-black font-medium" x-text="duration + ' jam'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Harga/jam</dt>
                                    <dd class="text-brand-black font-medium" x-text="formatRupiah(hargaPerJam)"></dd>
                                </div>
                            </dl>

                            <div class="flex justify-between items-center py-4">
                                <span class="font-semibold text-brand-black">Total</span>
                                <span class="font-display font-bold text-2xl text-brand-dark" x-text="formatRupiah(total)"></span>
                            </div>

                            <button
                                type="button"
                                @click="confirmPayment()"
                                :disabled="!isFormValid()"
                                class="w-full py-3.5 rounded-full bg-brand text-brand-black font-semibold hover:bg-[#4fd43f] disabled:opacity-40 disabled:cursor-not-allowed transition-colors duration-200"
                            >
                                Konfirmasi Pembayaran
                            </button>
                            <p class="text-xs text-gray-400 text-center mt-3" x-show="!isFormValid()">
                                Lengkapi data, jam, dan metode pembayaran dulu ya.
                            </p>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </template>

    {{-- ================= HALAMAN KONFIRMASI ================= --}}
    <template x-if="submitted">
        <div class="max-w-lg mx-auto px-4 py-20 md:py-28 text-center">
            <div class="w-16 h-16 rounded-full bg-court-available/15 flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-court-available" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </div>
            <h1 class="font-display font-bold text-2xl md:text-3xl text-brand-black">Booking Berhasil Dibuat!</h1>
            <p class="text-gray-500 mt-2">Simpan nomor pesanan ini untuk memantau status reservasi kamu.</p>

            <div class="mt-8 border border-gray-200 rounded-2xl p-6 text-left">
                <p class="text-xs text-gray-400 mb-1">Nomor Pesanan</p>
                <div class="flex items-center justify-between gap-3 pb-4 border-b border-gray-100">
                    <span class="font-mono font-bold text-lg text-brand-black" x-text="orderNumber"></span>
                    <button type="button" @click="copyText(orderNumber)" class="text-xs text-brand-dark font-medium hover:underline shrink-0">Salin</button>
                </div>

                <div class="flex items-center justify-between py-4 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Menunggu Verifikasi Admin
                    </span>
                </div>

                <dl class="space-y-2.5 pt-4 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Lapangan</dt><dd class="font-medium text-brand-black">{{ $lapangan->nama }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tanggal</dt><dd class="font-medium text-brand-black" x-text="tanggal"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Jam</dt><dd class="font-medium text-brand-black" x-text="jamMulaiLabel + ' - ' + jamSelesaiLabel"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Metode</dt><dd class="font-medium text-brand-black" x-text="metode === 'qris' ? 'QRIS' : 'Virtual Account BCA'"></dd></div>
                    <div class="flex justify-between pt-2 border-t border-gray-100"><dt class="font-semibold text-brand-black">Total</dt><dd class="font-bold text-brand-dark" x-text="formatRupiah(total)"></dd></div>
                </dl>
            </div>

            <p class="text-xs text-gray-400 mt-6 leading-relaxed">
                Akun otomatis dibuat menggunakan email <span class="font-medium text-gray-600" x-text="email"></span>
                dengan password default <span class="font-mono text-gray-600">smsport262</span>.
                Silakan login dan segera ganti password kamu.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/cek-status') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-brand text-brand-black text-sm font-semibold hover:bg-[#4fd43f] transition-colors duration-200">
                    Cek Status Pesanan
                </a>
                <a href="{{ url('/#reservasi') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-gray-300 text-gray-700 text-sm font-semibold hover:border-brand hover:text-brand-dark transition-colors duration-200">
                    Kembali ke Reservasi
                </a>
            </div>
        </div>
    </template>
</div>

@endsection