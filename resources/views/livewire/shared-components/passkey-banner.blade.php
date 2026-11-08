{{--
    Popup ajakan daftar Passkey.

    Server (PasskeyBanner::mount) sudah memastikan $show=true HANYA kalau user
    sudah login TAPI belum punya passkey (cek credential webauthn-passwordless
    via Keycloak admin API). localStorage cuma untuk "Jangan tampilkan lagi"
    per-browser. Begitu user daftar passkey, $show jadi false -> popup hilang
    di semua device.

    Kartu pojok kanan bawah (teleport ke body) tanpa overlay. x-data inline + x-init (aman thd wire:navigate,
    tidak pakai Alpine magic global). Lihat memory reference_alpine_magic_wire_navigate.
--}}
<div>
    @if ($show)
        <div x-data="{
                open: false,
                dontShow: false,
                key: 'announce_passkey_v1',
                init() {
                    try {
                        if (localStorage.getItem(this.key) !== '1') {
                            setTimeout(() => { this.open = true }, 400);
                        }
                    } catch (e) { this.open = true; }
                },
                close() {
                    if (this.dontShow) {
                        try { localStorage.setItem(this.key, '1'); } catch (e) {}
                    }
                    this.open = false;
                }
            }"
            x-init="init()">

            <template x-teleport="body">
                {{-- Kartu pojok kanan bawah, tanpa overlay: tidak menghalangi halaman.
                     Di HP dinaikkan supaya tidak menutup navbar bawah. --}}
                <div x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-on:keydown.escape.window="close()"
                    role="dialog" aria-labelledby="passkey-card-title"
                    class="fixed inset-x-3 bottom-24 z-[110] overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_24px_60px_-20px_rgb(15_23_42/.4)] sm:inset-x-auto sm:right-6 sm:w-[380px] md:bottom-6 dark:border-slate-800 dark:bg-slate-900">
                    <div class="ks-strip"></div>
                    <div class="p-5">
                        <div class="flex items-start gap-3">
                            <span class="grid size-11 shrink-0 place-items-center rounded-2xl bg-ks-soft text-ks-teal-dark dark:bg-teal-500/15 dark:text-teal-300">
                                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12C2 6.5 6.5 2 12 2a10 10 0 0 1 8 4" /><path d="M5 19.5C5.5 18 6 15 6 12a6 6 0 0 1 .34-2" /><path d="M17.29 21.02c.12-.6.43-2.3.5-3.02" /><path d="M12 10a2 2 0 0 0-2 2c0 1.02-.1 2.51-.26 4" /><path d="M8.65 22c.21-.66.45-1.32.57-2" /><path d="M14 13.12c0 2.38 0 6.38-1 8.88" /><path d="M2 16h.01" /><path d="M21.8 16c.2-2 .131-5.354 0-6" /><path d="M9 6.8a6 6 0 0 1 9 5.2c0 .47 0 1.17-.02 2" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 id="passkey-card-title" class="font-semibold text-slate-900 dark:text-white">Masuk tanpa kata sandi</h3>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                                    Daftarkan passkey — cukup sidik jari, wajah, atau PIN perangkat. Berlaku untuk semua aplikasi yang terhubung Kisara SSO.
                                </p>
                            </div>
                            <button type="button" x-on:click="close()" aria-label="Tutup"
                                class="-mr-1 -mt-1 grid size-8 shrink-0 place-items-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18" /><path d="m6 6 12 12" /></svg>
                            </button>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ $setupUrl }}" class="inline-flex h-10 items-center rounded-full bg-ks-teal px-4 text-sm font-semibold text-white hover:bg-ks-teal-dark">Daftarkan passkey</a>
                            <button type="button" x-on:click="close()" class="inline-flex h-10 items-center rounded-full px-4 text-sm font-semibold text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">Nanti saja</button>
                        </div>
                        <label class="mt-3 flex cursor-pointer select-none items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <input type="checkbox" x-model="dontShow" class="rounded border-slate-300 text-ks-teal focus:ring-ks-teal/40 dark:border-slate-600" />
                            Jangan tampilkan lagi
                        </label>
                    </div>
                </div>
            </template>
        </div>
    @endif
</div>
