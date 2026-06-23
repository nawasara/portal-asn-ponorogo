{{--
    Popup pengumuman: Reset MFA kini bisa mandiri via Email.
    Murni Alpine + localStorage (tanpa Livewire/server). "Jangan tampilkan lagi"
    disimpan per-browser. Ganti STORAGE_KEY versi (_vN) bila ada pengumuman baru.

    x-data inline + init di x-init agar aman terhadap wire:navigate (tidak pakai
    Alpine magic global). Lihat memory reference_alpine_magic_wire_navigate.
--}}
@php($storageKey = 'announce_reset_mfa_email_v1')

<div x-data="{
        open: false,
        dontShow: false,
        key: '{{ $storageKey }}',
        init() {
            // Tampilkan hanya kalau user belum memilih 'jangan tampilkan lagi'.
            try {
                if (localStorage.getItem(this.key) !== '1') {
                    // beri jeda kecil supaya tidak bentrok dgn transisi halaman
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

                        {{-- Icon --}}
                        <div class="mx-auto mb-5 size-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center shadow-lg shadow-emerald-500/30">
                            <svg class="size-7 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                            </svg>
                        </div>

                        <div class="text-center">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 text-xs font-semibold px-3 py-1 mb-3">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span> Info Terbaru
                            </span>
                            <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">
                                Reset MFA Kini Bisa via Email
                            </h3>
                            <p class="mt-2.5 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                Lupa atau ganti perangkat MFA? Kini Anda bisa <b>reset MFA secara mandiri</b>
                                lewat <b>email</b> yang terdaftar — tanpa perlu menunggu admin.
                                Kode OTP dikirim langsung ke email Anda.
                            </p>
                        </div>

                        <div class="mt-6 flex flex-col gap-2.5">
                            {{-- full page load (tanpa wire:navigate) supaya Alpine x-mask & form di /reset-mfa ke-init dengan benar --}}
                            <a href="{{ url('/reset-mfa') }}"
                                class="relative inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white overflow-hidden group">
                                <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                                <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                                <span class="relative">Reset MFA Sekarang</span>
                            </a>

                            <button type="button" x-on:click="close()"
                                class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition">
                                Mengerti
                            </button>
                        </div>

                        {{-- Jangan tampilkan lagi --}}
                        <label class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-500 dark:text-slate-400 cursor-pointer select-none">
                            <input type="checkbox" x-model="dontShow"
                                class="rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500/40" />
                            Jangan tampilkan pengumuman ini lagi
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
