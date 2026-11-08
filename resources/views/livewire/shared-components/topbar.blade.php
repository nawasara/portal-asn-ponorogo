<div>
    <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-slate-50/85 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/85">
        <nav class="mx-auto flex h-16 max-w-6xl items-center gap-6 px-4 sm:px-6">

            <a href="/" wire:navigate.hover class="flex items-center gap-2.5">
                <x-kisara-mark name="portal-asn" class="size-9 shrink-0" />
                <span class="leading-tight">
                    <span class="block text-[17px] tracking-tight"><span class="text-slate-500 dark:text-slate-400">Kisara</span> <b class="font-semibold text-slate-900 dark:text-white">Portal ASN</b></span>
                    <span class="hidden text-[11px] text-slate-500 sm:block dark:text-slate-400">Pemerintah Kabupaten Ponorogo</span>
                </span>
            </a>

            <div class="ml-auto hidden items-center gap-1 text-sm font-medium text-slate-600 md:flex dark:text-slate-300">
                @if (Route::is('index'))
                    <a href="#app-lists" class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">Aplikasi</a>
                    <a href="#support" class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">Bantuan</a>
                    <a href="#integration" class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">Integrasi</a>
                    <a href="#faq" class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">FAQ</a>
                @else
                    <a href="{{ route('apps') }}" wire:navigate.hover class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">Aplikasi</a>
                    <a href="{{ route('supports') }}" wire:navigate.hover class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">Bantuan</a>
                    <a href="{{ route('integrations') }}" wire:navigate.hover class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">Integrasi</a>
                    <a href="{{ url('/#faq') }}" class="rounded-full px-3.5 py-2 hover:bg-slate-200/60 dark:hover:bg-slate-800">FAQ</a>
                @endif
            </div>

            <div class="ml-auto flex items-center gap-1.5 md:ml-0">
                <x-dark-mode />

                @auth
                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                        <button id="portal-user-dropdown" type="button"
                            class="hs-dropdown-toggle flex h-10 cursor-pointer items-center gap-2 rounded-full py-1 pl-1 pr-3 hover:bg-slate-200/60 dark:hover:bg-slate-800"
                            aria-haspopup="menu" aria-expanded="false" aria-label="Menu akun">
                            <span class="grid size-8 place-items-center rounded-full bg-ks-teal text-xs font-semibold text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden max-w-[10rem] truncate text-sm font-medium text-slate-700 sm:inline dark:text-slate-200">{{ auth()->user()->name }}</span>
                            <svg class="hs-dropdown-open:rotate-180 size-4 text-slate-400 transition" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                        </button>

                        <div class="hs-dropdown-menu transition-[opacity,margin] mt-2 hs-dropdown-open:opacity-100 opacity-0 hidden w-72 rounded-2xl border border-slate-200 bg-white p-2 ks-card-shadow dark:border-slate-800 dark:bg-slate-900"
                            role="menu" aria-orientation="vertical" aria-labelledby="portal-user-dropdown">
                            <div class="border-b border-slate-100 px-3 pb-3 pt-2 dark:border-slate-800">
                                <p class="text-xs text-slate-500">Masuk sebagai</p>
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            </div>
                            {{-- Profil, keamanan, perangkat, dan layanan dikelola di Akun Kisara --}}
                            <a href="{{ account_url() }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ks-teal hover:bg-ks-soft dark:text-teal-300 dark:hover:bg-teal-500/10">
                                <x-kisara-mark name="akun-kisara" class="size-5" /> Kelola Akun Kisara
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 17 5-5-5-5" /><path d="M21 12H9" /><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="inline-flex h-10 items-center gap-2 rounded-full bg-ks-teal px-5 text-sm font-semibold text-white hover:bg-ks-teal-dark">Masuk</a>
                @endauth
            </div>
        </nav>
    </header>
</div>
