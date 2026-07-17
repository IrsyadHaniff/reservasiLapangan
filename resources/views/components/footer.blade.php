<footer id="contact" aria-label="Contact dan Informasi Footer" class="relative bg-brand-black text-gray-300 scroll-mt-16 md:scroll-mt-20">

    {{-- Dekorasi glow tipis biar gak flat --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-96 bg-brand/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">

        {{-- CTA WhatsApp banner --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 bg-gradient-to-r from-brand-dark/60 to-brand-dark/20 border border-brand/20 rounded-2xl p-6 md:p-8 mb-14">
            <div class="text-center md:text-left">
                <h2 class="font-display font-bold text-xl md:text-2xl text-white">Masih Ragu? Tanya Dulu Aja</h2>
                <p class="text-gray-400 text-sm mt-1">Tim kami siap bantu jawab pertanyaan seputar jadwal & harga.</p>
            </div>
            <a href="https://wa.me/6281234567890?text=Halo%20SM-SPORT%20CENTER%2C%20saya%20mau%20tanya%20ketersediaan%20lapangan"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-brand text-brand-black font-semibold text-sm hover:bg-[#4fd43f] transition-colors duration-200 shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2Zm5.8 14.02c-.24.68-1.4 1.3-1.93 1.38-.5.08-1.12.11-1.8-.11-.42-.13-.95-.3-1.64-.6-2.88-1.24-4.76-4.15-4.9-4.35-.14-.19-1.17-1.55-1.17-2.96 0-1.4.73-2.09 1-2.38.26-.28.57-.35.76-.35h.55c.18 0 .42-.07.65.5.24.58.8 2 .87 2.15.07.14.11.31.02.5-.09.19-.14.31-.28.47-.14.16-.29.36-.42.48-.14.14-.28.29-.12.57.16.28.71 1.17 1.52 1.9 1.05.94 1.93 1.23 2.21 1.37.28.14.44.12.6-.07.16-.19.68-.79.87-1.06.18-.28.36-.23.6-.14.24.09 1.53.72 1.79.85.26.14.44.2.5.31.07.12.07.65-.16 1.33Z"/>
                </svg>
                Chat WhatsApp
            </a>
        </div>

        {{-- Grid utama --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="{{ url('/#top') }}" class="flex items-center gap-2.5 mb-4">
                    <img src="{{ asset('assets/img/icon.webp') }}" alt="" width="36" height="36" loading="lazy" class="w-9 h-9">
                    <span class="font-display font-bold text-lg text-white">
                        SM-<span class="text-brand">SPORT</span> CENTER
                    </span>
                </a>
                <p class="text-sm text-gray-400 leading-relaxed max-w-xs">
                    Lapangan futsal & badminton indoor dengan sistem reservasi online, cepat dan real-time.
                </p>
                {{-- Sosial media --}}
                <div class="flex items-center gap-3 mt-5">
                    <a href="#" aria-label="Instagram SM-SPORT CENTER" class="flex items-center justify-center w-9 h-9 rounded-full bg-white/5 hover:bg-brand hover:text-brand-black transition-colors duration-200">
                        <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069ZM12 0C8.741 0 8.332.014 7.052.072 2.695.272.273 2.69.073 7.052.014 8.332 0 8.741 0 12s.014 3.668.072 4.948c.2 4.358 2.618 6.78 6.98 6.98C8.332 23.986 8.741 24 12 24s3.668-.014 4.948-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948s-.014-3.668-.072-4.948C23.73 2.69 21.31.273 16.951.072 15.668.014 15.259 0 12 0Zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324ZM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881Z"/></svg>
                    </a>
                    <a href="#" aria-label="Facebook SM-SPORT CENTER" class="flex items-center justify-center w-9 h-9 rounded-full bg-white/5 hover:bg-brand hover:text-brand-black transition-colors duration-200">
                        <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12.06C22 6.505 17.523 2 12 2S2 6.505 2 12.06c0 5.02 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.877h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.92 8.437-9.94Z"/></svg>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp SM-SPORT CENTER" class="flex items-center justify-center w-9 h-9 rounded-full bg-white/5 hover:bg-brand hover:text-brand-black transition-colors duration-200">
                        <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.96L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2Z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <h3 class="text-white font-semibold text-sm mb-4">Navigasi</h3>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ url('/#top') }}" class="hover:text-brand transition-colors duration-200">Home</a></li>
                    <li><a href="{{ url('/#reservasi') }}" class="hover:text-brand transition-colors duration-200">Reservasi</a></li>
                    <li><a href="{{ url('/#about') }}" class="hover:text-brand transition-colors duration-200">About</a></li>
                    <li><a href="{{ url('/#contact') }}" class="hover:text-brand transition-colors duration-200">Contact</a></li>
                </ul>
            </div>

            {{-- Jam Operasional --}}
            <div>
                <h3 class="text-white font-semibold text-sm mb-4">Jam Operasional</h3>
                <ul class="space-y-2.5 text-sm">
                    <li class="flex justify-between gap-4"><span>Senin – Jumat</span><span class="text-gray-400">06.00 – 24.00</span></li>
                    <li class="flex justify-between gap-4"><span>Sabtu – Minggu</span><span class="text-gray-400">06.00 – 24.00</span></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h3 class="text-white font-semibold text-sm mb-4">Kontak</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4.5 h-4.5 text-brand shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span>Jl. Contoh Raya No. 123, Pasarkemis, Tangerang, Banten</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4.5 h-4.5 text-brand shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a1.5 1.5 0 0 0 1.5-1.5v-2.372a1.5 1.5 0 0 0-1.06-1.435l-3.483-1.045a1.5 1.5 0 0 0-1.657.494l-.883 1.146a11.25 11.25 0 0 1-5.752-5.752l1.146-.883a1.5 1.5 0 0 0 .494-1.657l-1.045-3.483a1.5 1.5 0 0 0-1.435-1.06H3.75a1.5 1.5 0 0 0-1.5 1.5Z" />
                        </svg>
                        <a href="tel:+6281234567890" class="hover:text-brand transition-colors duration-200">+62 812-3456-7890</a>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4.5 h-4.5 text-brand shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <a href="mailto:halo@smsportcenter.com" class="hover:text-brand transition-colors duration-200">halo@smsportcenter.com</a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-14 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} SM-SPORT CENTER. Seluruh hak cipta dilindungi.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-brand transition-colors duration-200">Kebijakan Privasi</a>
                <a href="#" class="hover:text-brand transition-colors duration-200">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>