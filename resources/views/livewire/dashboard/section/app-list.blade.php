<div>
    <section id="app-lists" class="scroll-mt-24 py-10">

        @if (Route::is('apps'))
            <div class="mb-6 flex items-center gap-2 md:hidden">
                <x-kisara-mark name="portal-asn" class="size-6" />
                <span class="font-semibold text-slate-700 dark:text-white">Aplikasi terhubung Kisara SSO</span>
            </div>
        @endif

        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                    Aplikasi
                    <span class="ml-1 align-middle text-sm font-semibold text-ks-teal dark:text-teal-300">{{ count($this->filteredApps) }} {{ $query ? 'hasil' : 'tersedia' }}</span>
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih aplikasi untuk melanjutkan dengan sesi Kisara SSO Anda.</p>
            </div>

            <label class="flex h-11 w-full items-center gap-2 rounded-full border border-slate-300 bg-white px-4 sm:w-72 dark:border-slate-700 dark:bg-slate-900">
                <svg class="size-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
                <input type="text" wire:model.live.debounce.200ms="query" placeholder="Cari aplikasi..."
                    class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400 dark:text-white" />
                @if ($query)
                    <button type="button" wire:click="$set('query', '')" class="grid size-5 shrink-0 place-items-center rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" aria-label="Hapus pencarian">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18" /><path d="m6 6 12 12" /></svg>
                    </button>
                @endif
            </label>
        </div>

        @if (count($this->filteredApps) === 0)
            <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-10 text-center dark:border-slate-800 dark:bg-slate-900">
                <svg class="mx-auto mb-3 size-12 text-slate-300 dark:text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.3-4.3" /></svg>
                <h3 class="font-semibold text-slate-700 dark:text-slate-200">Tidak ada aplikasi ditemukan</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Coba kata kunci lain atau
                    <button type="button" wire:click="$set('query', '')" class="font-semibold text-ks-teal hover:underline dark:text-teal-300">kosongkan pencarian</button>.
                </p>
            </div>
        @else
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->filteredApps as $app)
                    <a href="{{ $app['link'] }}" wire:key="app-{{ $loop->index }}-{{ $app['slug'] }}"
                        class="group flex flex-col rounded-3xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-ks-teal/40 hover:ks-card-shadow dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between">
                            @if ($app['icon'])
                                <span class="grid size-12 place-items-center rounded-2xl bg-white p-2 ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                                    <img src="{{ $app['icon'] }}" alt="" class="max-h-full max-w-full object-contain" loading="lazy" />
                                </span>
                            @else
                                {{-- Aplikasi tanpa logo di katalog: singkatan + warna dari panel Nawasara --}}
                                <span class="grid size-12 place-items-center rounded-2xl text-base font-bold text-white" style="background: {{ $app['color'] ?: '#0f8b8d' }}">{{ $app['abbreviation'] }}</span>
                            @endif
                            @if ($app['status'] === 'connected')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-ks-soft px-2.5 py-1 text-[11px] font-semibold text-ks-teal-dark dark:bg-teal-500/15 dark:text-teal-300"><span class="size-1.5 rounded-full bg-ks-teal"></span>Terhubung</span>
                            @else
                                <span class="rounded-full bg-amber-100/80 px-2.5 py-1 text-[11px] font-semibold text-amber-700 dark:bg-amber-500/15 dark:text-amber-300">Segera hadir</span>
                            @endif
                        </div>
                        <h3 class="mt-4 text-[17px] font-semibold tracking-tight text-slate-900 dark:text-white">{{ $app['name'] }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $app['description'] }}</p>
                        <span class="mt-auto inline-flex items-center gap-1.5 pt-4 text-sm font-semibold text-ks-teal dark:text-teal-300">
                            Buka aplikasi
                            <svg class="size-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7" /></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>
