{{--
    Popup ajakan daftar Passkey.

    Server (PasskeyBanner::mount) sudah memastikan $show=true HANYA kalau user
    sudah login TAPI belum punya passkey (cek credential webauthn-passwordless
    via Keycloak admin API). localStorage cuma untuk "Jangan tampilkan lagi"
    per-browser. Begitu user daftar passkey, $show jadi false -> popup hilang
    di semua device.

    Style meniru announcement-mfa (teleport ke body, glass-card) supaya tidak
    tabrakan dengan navbar. x-data inline + x-init (aman thd wire:navigate,
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
                <div x-show="open" x-cloak class="fixed inset-0 z-[110]">

                    {{-- Overlay --}}
                    <div x-show="open" x-transition.opacity x-on:click="close()"
                        class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/70 backdrop-blur-md"></div>

                    {{-- Panel --}}
                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4">
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-on:keydown.escape.window="close()"
                                class="relative w-full max-w-md glass-card gradient-border rounded-3xl p-6 sm:p-8 shadow-2xl shadow-slate-900/20">

                                {{-- Tombol close (X) --}}
                                <button type="button" x-on:click="close()"
                                    class="absolute top-4 right-4 size-8 grid place-items-center rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition"
                                    aria-label="Tutup">
                                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>

                                {{-- Icon fingerprint --}}
                                <div class="mx-auto mb-5 size-14 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 grid place-items-center shadow-lg shadow-red-500/30">
                                    <svg class="size-7 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12C2 6.5 6.5 2 12 2a10 10 0 0 1 8 4" />
                                        <path d="M5 19.5C5.5 18 6 15 6 12a6 6 0 0 1 .34-2" />
                                        <path d="M17.29 21.02c.12-.6.43-2.3.5-3.02" />
                                        <path d="M12 10a2 2 0 0 0-2 2c0 1.02-.1 2.51-.26 4" />
                                        <path d="M8.65 22c.21-.66.45-1.32.57-2" />
                                        <path d="M14 13.12c0 2.38 0 6.38-1 8.88" />
                                        <path d="M2 16h.01" />
                                        <path d="M21.8 16c.2-2 .131-5.354 0-6" />
                                        <path d="M9 6.8a6 6 0 0 1 9 5.2c0 .47 0 1.17-.02 2" />
                                    </svg>
                                </div>

                                <div class="text-center">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-300 text-xs font-semibold px-3 py-1 mb-3">
                                        <span class="size-1.5 rounded-full bg-red-500"></span> Keamanan Baru
                                    </span>
                                    <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">
                                        Amankan Akun dengan Passkey
                                    </h3>
                                    <p class="mt-2.5 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Login lebih cepat &amp; aman <b>tanpa password</b> — cukup sidik jari,
                                        wajah, atau PIN perangkat Anda. Passkey berlaku untuk
                                        <b>semua aplikasi Pemkab Ponorogo</b>.
                                    </p>
                                </div>

                                <div class="mt-6 flex flex-col gap-2.5">
                                    <a href="{{ $setupUrl }}"
                                        class="relative inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white overflow-hidden group no-underline">
                                        <span class="absolute inset-0 bg-gradient-to-br from-red-500 to-rose-600"></span>
                                        <span class="absolute -inset-1 bg-gradient-to-br from-red-400 to-orange-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                                        <span class="relative">Daftar Passkey Sekarang</span>
                                    </a>

                                    <button type="button" x-on:click="close()"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition">
                                        Nanti saja
                                    </button>
                                </div>

                                {{-- Jangan tampilkan lagi --}}
                                <label class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-500 dark:text-slate-400 cursor-pointer select-none">
                                    <input type="checkbox" x-model="dontShow"
                                        class="rounded border-slate-300 dark:border-slate-600 text-red-600 focus:ring-red-500/40" />
                                    Jangan tampilkan pengumuman ini lagi
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif
</div>
