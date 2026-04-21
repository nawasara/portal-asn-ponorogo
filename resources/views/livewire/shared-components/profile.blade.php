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

                    <a href="{{ route('update-whatsapp-number') }}" wire:navigate.hover
                        class="flex items-center gap-4 p-4 hover:bg-white/40 dark:hover:bg-white/5 transition group">
                        <div class="size-10 shrink-0 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center shadow-md shadow-emerald-500/20">
                            <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold dark:text-white">Perbarui Nomor WhatsApp</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Ubah nomor untuk verifikasi OTP</div>
                        </div>
                        <svg class="size-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </a>

                    <a href="{{ route('mfa.reset') }}" wire:navigate.hover
                        class="flex items-center gap-4 p-4 hover:bg-white/40 dark:hover:bg-white/5 transition group">
                        <div class="size-10 shrink-0 rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 grid place-items-center shadow-md shadow-sky-500/20">
                            <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold dark:text-white">Reset MFA</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">Reset Multi-Factor Authentication</div>
                        </div>
                        <svg class="size-4 text-slate-400 group-hover:text-sky-600 group-hover:translate-x-0.5 transition shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
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
                        <a href="{{ route('mfa.reset') }}"
                            class="inline-flex items-center justify-center gap-2 glass-card rounded-xl px-5 py-3 text-sm font-semibold dark:text-white hover:bg-white/80 dark:hover:bg-white/10 transition">
                            Reset MFA
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </section>
</div>
