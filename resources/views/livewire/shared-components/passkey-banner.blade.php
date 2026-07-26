{{--
    Banner ajakan daftar Passkey. Inline (bukan popup mengganggu), dismissible
    per-browser via localStorage. Server sudah memastikan $show=true hanya kalau
    user belum punya passkey. localStorage cuma untuk "nanti saja" sesi ini.

    x-data inline + init di x-init (aman terhadap wire:navigate — tidak pakai
    Alpine magic global). Lihat memory reference_alpine_magic_wire_navigate.
--}}
<div>
    @if ($show)
        <div x-data="{
                open: false,
                key: 'passkey_banner_dismissed_v1',
                init() {
                    try {
                        if (localStorage.getItem(this.key) !== '1') {
                            setTimeout(() => { this.open = true }, 300);
                        }
                    } catch (e) { this.open = true; }
                },
                later() {
                    try { localStorage.setItem(this.key, '1'); } catch (e) {}
                    this.open = false;
                }
            }"
            x-init="init()"
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="mx-auto max-w-5xl px-4 mt-4">

            <div class="relative flex flex-col sm:flex-row sm:items-center gap-4 rounded-2xl border border-red-200 dark:border-red-900/50 bg-gradient-to-r from-red-50 to-white dark:from-red-950/30 dark:to-neutral-900 p-4 sm:p-5 shadow-sm">

                {{-- Ikon fingerprint --}}
                <div class="shrink-0 grid place-items-center size-11 rounded-xl bg-red-600 text-white shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                {{-- Teks --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm sm:text-base font-semibold text-neutral-800 dark:text-neutral-100">
                        Amankan akun Anda dengan Passkey
                    </h3>
                    <p class="mt-0.5 text-xs sm:text-sm text-neutral-600 dark:text-neutral-300">
                        Login lebih cepat & aman tanpa password — cukup sidik jari, wajah, atau PIN perangkat Anda.
                        Passkey berlaku untuk semua aplikasi Pemkab Ponorogo.
                    </p>
                </div>

                {{-- Aksi --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ $setupUrl }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 px-4 py-2 text-sm font-semibold text-white transition no-underline">
                        Daftar Passkey
                    </a>
                    <button type="button" x-on:click="later()"
                        class="rounded-xl px-3 py-2 text-sm font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200 transition">
                        Nanti saja
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
