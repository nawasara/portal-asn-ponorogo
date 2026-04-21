<div>
    <header class="hidden md:block px-4 pt-4">
        <nav class="max-w-6xl mx-auto glass rounded-2xl shadow-sm shadow-slate-900/5">
            <div class="flex items-center justify-between gap-x-6 px-5 py-3">

                <a href="/" wire:navigate.hover class="flex items-center gap-x-2.5 group">
                    <span class="sr-only">Portal ASN Ponorogo</span>
                    <div class="relative">
                        <div class="absolute inset-0 bg-emerald-400 rounded-xl blur-md opacity-60 group-hover:opacity-90 transition"></div>
                        <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="relative h-10 w-auto drop-shadow" />
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-sm font-semibold tracking-tight dark:text-white">Portal ASN Ponorogo</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400">Satu akun, semua layanan</div>
                    </div>
                </a>

                <div class="hidden lg:flex items-center gap-1 glass-card rounded-full px-1.5 py-1.5 text-sm">
                    @if (Route::is('index'))
                        <a href="#app-lists"   class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">Aplikasi</a>
                        <a href="#support"     class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">Bantuan</a>
                        <a href="#integration" class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">Integrasi</a>
                        <a href="#faq"         class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">FAQ</a>
                    @else
                        <a href="{{ route('apps') }}"         wire:navigate.hover class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">Aplikasi</a>
                        <a href="{{ route('supports') }}"     wire:navigate.hover class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">Bantuan</a>
                        <a href="{{ route('integrations') }}" wire:navigate.hover class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">Integrasi</a>
                        <a href="{{ url('/#faq') }}"          class="px-4 py-1.5 rounded-full hover:bg-white/60 dark:hover:bg-white/5 transition dark:text-white">FAQ</a>
                    @endif
                </div>

                <div class="flex items-center gap-x-1.5">

                    {{-- Dark mode toggle --}}
                    <x-dark-mode />

                    @auth
                        <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                            <button id="portal-user-dropdown" type="button"
                                class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm cursor-pointer font-medium rounded-lg text-slate-700 hover:bg-slate-100 dark:text-white dark:hover:bg-white/10 transition"
                                aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                <span class="size-7 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 grid place-items-center text-white text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden sm:inline max-w-[10rem] truncate">{{ auth()->user()->name }}</span>
                                <svg class="hs-dropdown-open:rotate-180 size-4 transition" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>

                            <div class="hs-dropdown-menu transition-[opacity,margin] mt-3 hs-dropdown-open:opacity-100 opacity-0 hidden min-w-64 glass rounded-2xl shadow-xl shadow-slate-900/10 p-2"
                                role="menu" aria-orientation="vertical" aria-labelledby="portal-user-dropdown">

                                <div class="px-3 py-3 mb-1 border-b border-slate-200/60 dark:border-slate-700/40">
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Login sebagai</p>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>

                                <a href="{{ route('update-whatsapp-number') }}"
                                    class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/5 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="size-4 text-slate-500 dark:text-slate-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                    </svg>
                                    Perbarui Nomor WhatsApp
                                </a>

                                <a href="{{ route('mfa.reset') }}"
                                    class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/5 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="size-4 text-slate-500 dark:text-slate-400">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    Reset MFA
                                </a>

                                <div class="mt-1 pt-1 border-t border-slate-200/60 dark:border-slate-700/40">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="size-4">
                                                <path d="m16 17 5-5-5-5" />
                                                <path d="M21 12H9" />
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="/login"
                            class="relative inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold text-white overflow-hidden group">
                            <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                            <span class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-sky-500 opacity-0 group-hover:opacity-100 transition"></span>
                            <span class="relative">Masuk</span>
                            <svg class="relative size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>
</div>
