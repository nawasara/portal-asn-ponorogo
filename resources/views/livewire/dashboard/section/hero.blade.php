<div>
    <section id="hero" class="pt-28 pb-12 lg:pt-36 lg:pb-16">
        <div class="grid lg:grid-cols-[1.2fr_1fr] gap-10 lg:gap-16 items-center">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="inline-flex items-center gap-2 glass-card rounded-full px-4 py-1.5 text-xs font-medium mb-6">
                <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                @auth
                    Terhubung sebagai <span class="font-semibold text-emerald-600 dark:text-emerald-400">ASN</span>
                @else
                    Tersambung dengan <span class="font-semibold text-emerald-600 dark:text-emerald-400">Keycloak SSO</span>
                @endauth
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1]">
                @auth
                    <span class="dark:text-white">Selamat datang,</span>
                    <br>
                    <span class="text-gradient">{{ auth()->user()->name }}</span>
                @else
                    <span class="dark:text-white">Satu portal,</span>
                    <br>
                    <span class="text-gradient">banyak layanan</span>
                @endauth
            </h1>

            <p class="mt-6 max-w-xl text-base md:text-lg text-slate-600 dark:text-slate-300 leading-relaxed">
                @auth
                    Sekarang Anda dapat mengakses
                @else
                    Akses
                @endauth
                seluruh aplikasi ASN Pemerintah Kabupaten Ponorogo dalam satu tempat —
                <span class="font-semibold text-slate-800 dark:text-slate-100">cepat, aman, terintegrasi.</span>
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                @auth
                    <a href="#app-lists"
                        class="relative inline-flex items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-base font-semibold text-white overflow-hidden group">
                        <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                        <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-xl opacity-50 group-hover:opacity-80 transition"></span>
                        <svg class="relative size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.34-4.34" />
                        </svg>
                        <span class="relative">Cari Aplikasi</span>
                    </a>
                @else
                    <a href="/login"
                        class="relative inline-flex items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-base font-semibold text-white overflow-hidden group">
                        <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                        <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-xl opacity-50 group-hover:opacity-80 transition"></span>
                        <svg class="relative size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m10 17 5-5-5-5" />
                            <path d="M15 12H3" />
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        </svg>
                        <span class="relative">Masuk Sekarang</span>
                    </a>
                @endauth

                <a href="#support" @click="$dispatch('set-arrow');"
                    class="inline-flex items-center justify-center gap-2 glass-card rounded-2xl px-6 py-3.5 text-base font-semibold hover:bg-white/80 dark:hover:bg-white/10 transition dark:text-white">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4" />
                        <path d="M12 8h.01" />
                    </svg>
                    Bantuan
                </a>
            </div>
        </div>

        {{-- Floating card stack --}}
        <div class="relative hidden lg:block animate-float" data-aos="fade-up" data-aos-duration="1500"
            data-aos-delay="100">
            <div class="relative glass-card gradient-border rounded-3xl p-6 shadow-2xl shadow-emerald-500/10">
                <div class="flex items-center gap-3 mb-5">
                    <div class="size-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center text-white font-bold">S</div>
                    <div>
                        <div class="font-semibold dark:text-white">Simashebat</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Sistem Manajemen ASN</div>
                    </div>
                    <span class="ml-auto text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Live</span>
                </div>
                <div class="space-y-2">
                    <div class="h-2 rounded-full bg-slate-200/70 dark:bg-slate-700/50 overflow-hidden">
                        <div class="h-full w-3/4 bg-gradient-to-r from-emerald-400 to-sky-500 rounded-full"></div>
                    </div>
                    <div class="h-2 rounded-full bg-slate-200/70 dark:bg-slate-700/50 overflow-hidden">
                        <div class="h-full w-1/2 bg-gradient-to-r from-emerald-400 to-sky-500 rounded-full"></div>
                    </div>
                </div>
            </div>

            <div class="absolute -bottom-8 -left-6 glass-card rounded-2xl p-4 w-56 shadow-xl rotate-[-6deg]">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-gradient-to-br from-sky-400 to-blue-600 grid place-items-center">
                        <svg class="size-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold dark:text-white">Rakaca</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400">Tersambung</div>
                    </div>
                </div>
            </div>

            <div class="absolute -top-6 -right-4 glass-card rounded-2xl p-4 w-52 shadow-xl rotate-[5deg]">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-gradient-to-br from-violet-400 to-fuchsia-600 grid place-items-center">
                        <svg class="size-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold dark:text-white">MFA Aktif</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400">WhatsApp OTP</div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</div>
