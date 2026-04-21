<div>
    <section id="app-lists" class="py-12 lg:py-16">

        @if (Route::is('apps'))
            <div class="flex items-center justify-center gap-x-3 md:hidden mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-slate-700 font-semibold">Aplikasi Terintegrasi SSO Kisara</span>
            </div>
        @endif

        <div class="flex items-end justify-between flex-wrap gap-4 mb-8" data-aos="fade-up" data-aos-duration="1000">
            <div>
                <div class="flex items-baseline gap-3 mb-1">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight dark:text-white">Daftar Aplikasi</h2>
                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                        {{ count($this->filteredApps) }} {{ $query ? 'hasil' : 'tersedia' }}
                    </span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Pilih aplikasi untuk melanjutkan dengan sesi SSO Anda.</p>
            </div>

            <div class="relative glass-card rounded-full flex items-center gap-2 px-4 py-2.5 w-full sm:w-72">
                <svg class="size-4 text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.3-4.3" />
                </svg>
                <input type="text"
                    wire:model.live.debounce.200ms="query"
                    placeholder="Cari aplikasi..."
                    class="bg-transparent outline-none text-sm w-full placeholder:text-slate-400 dark:text-white" />
                @if ($query)
                    <button type="button" wire:click="$set('query', '')"
                        class="shrink-0 size-5 rounded-full grid place-items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition"
                        aria-label="Hapus pencarian">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        @if (count($this->filteredApps) === 0)
            <div class="glass-card rounded-2xl p-10 text-center" data-aos="fade-up">
                <svg class="size-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.3-4.3" />
                </svg>
                <h3 class="font-semibold text-slate-700 dark:text-slate-200">Tidak ada aplikasi ditemukan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Coba kata kunci lain atau
                    <button type="button" wire:click="$set('query', '')"
                        class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline">kosongkan pencarian</button>.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" data-aos="fade-up"
                data-aos-duration="1000">
                @foreach ($this->filteredApps as $app)
                    <a href="{{ $app['link'] }}"
                        class="tilt glass-card gradient-border rounded-3xl p-6 group relative block overflow-hidden"
                        wire:key="app-{{ $loop->index }}-{{ $app['name'] }}">
                        <div
                            class="absolute -top-20 -right-20 w-48 h-48 rounded-full bg-emerald-400/20 blur-3xl opacity-0 group-hover:opacity-100 transition duration-500">
                        </div>

                        <div class="relative flex items-start justify-between mb-6">
                            <div class="size-14 rounded-2xl glass-card grid place-items-center p-2.5 shadow-inner">
                                <img src="{{ $app['icon'] }}" alt="{{ $app['name'] }} Icon"
                                    class="max-h-full max-w-full object-contain" loading="lazy" />
                            </div>
                            @if ($app['status'] === 'connected')
                                <span
                                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-emerald-100/80 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                                    <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Terhubung
                                </span>
                            @else
                                <span
                                    class="text-[11px] font-semibold px-2.5 py-1 rounded-full bg-amber-100/80 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300">
                                    Segera Hadir
                                </span>
                            @endif
                        </div>

                        <h3 class="relative text-xl font-bold tracking-tight mb-2 dark:text-white">
                            {{ $app['name'] }}
                        </h3>
                        <p class="relative text-sm text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">
                            {{ $app['description'] }}
                        </p>

                        <div
                            class="relative mt-6 pt-4 border-t border-slate-200/60 dark:border-slate-700/40 flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-500 dark:text-slate-400">Buka aplikasi</span>
                            <span
                                class="size-8 grid place-items-center rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 group-hover:translate-x-1 transition">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M5 12h14M13 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>
