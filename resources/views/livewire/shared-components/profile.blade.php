<div>
    <section class="py-8 md:py-16">

        @if (Route::is('profiles'))
            <div class="flex items-center justify-center gap-x-3 md:hidden mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-slate-700 font-semibold">Profil SSO Kisara</span>
            </div>
        @endif

        <div class="max-w-md mx-auto" data-aos="fade-up" data-aos-duration="600" data-aos-once="true">

            @auth
                {{-- Header profil --}}
                <div class="glass-card gradient-border rounded-3xl p-6 text-center">
                    <div class="relative inline-block">
                        <div class="size-20 mx-auto rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center text-white text-3xl font-bold shadow-lg shadow-emerald-500/30">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-1 -right-1 size-5 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-900"></span>
                    </div>
                    <h2 class="mt-4 text-xl font-bold tracking-tight dark:text-white">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 break-all">
                        {{ auth()->user()->email }}
                    </p>
                    <div class="mt-3 inline-flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-emerald-100/80 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                        <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Terhubung dengan Kisara SSO
                    </div>
                </div>

                {{-- Action menu --}}
                <div class="mt-4 glass-card rounded-2xl overflow-hidden divide-y divide-slate-200/60 dark:divide-slate-700/40">

                    {{-- Profil, keamanan, perangkat, dan layanan dikelola di Akun Kisara.
                         Situs lain, jadi tanpa wire:navigate. --}}
                    <a href="{{ account_url() }}"
                        class="flex items-center gap-4 p-4 hover:bg-white/40 dark:hover:bg-white/5 transition group">
                        <div class="size-10 shrink-0 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-700 grid place-items-center shadow-md shadow-emerald-500/20">
                            <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <circle cx="12" cy="10" r="3" />
                                <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold dark:text-white">Kelola Akun Kisara</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Profil, keamanan, perangkat, email dinas, dan VPN</div>
                        </div>
                        <svg class="size-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </a>

                    {{-- Dark mode toggle --}}
                    <div class="flex items-center gap-4 p-4">
                        <div class="size-10 shrink-0 rounded-xl bg-gradient-to-br from-violet-400 to-fuchsia-600 grid place-items-center shadow-md shadow-violet-500/20">
                            <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold dark:text-white">Tampilan</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Mode terang atau gelap</div>
                        </div>
                        <x-dark-mode />
                    </div>
                </div>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-rose-600 dark:text-rose-400 glass-card hover:bg-rose-50/60 dark:hover:bg-rose-500/10 transition">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m16 17 5-5-5-5"/>
                            <path d="M21 12H9"/>
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        </svg>
                        Keluar
                    </button>
                </form>

            @else
                {{-- Guest view --}}
                <div class="glass-card gradient-border rounded-3xl p-8 text-center">
                    <div class="size-16 mx-auto rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center shadow-lg shadow-emerald-500/20 mb-4">
                        <svg class="size-8 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M4 21v-2a4 4 0 0 1 3-3.87"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight dark:text-white">Belum Masuk</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Masuk untuk mengakses profil dan semua layanan ASN Ponorogo.
                    </p>

                    <div class="mt-6 flex flex-col gap-3">
                        <a href="/login"
                            class="relative inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white overflow-hidden group">
                            <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                            <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                            <svg class="relative size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m10 17 5-5-5-5"/>
                                <path d="M15 12H3"/>
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            </svg>
                            <span class="relative">Masuk Sekarang</span>
                        </a>
                        <a href="{{ account_url('/reset-mfa') }}"
                            class="inline-flex items-center justify-center gap-2 glass-card rounded-xl px-5 py-3 text-sm font-semibold dark:text-white hover:bg-white/80 dark:hover:bg-white/10 transition">
                            Reset MFA
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </section>
</div>
