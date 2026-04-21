<div>
    <div x-data="{
        open: false,
        waNumber: '',
        openModal(e) {
            this.waNumber = e.detail.whatsappNumber ?? '0';
            this.waNumber = this.masking(this.waNumber);
            this.open = true;
        },
        masking(number) {
            number = number.slice(0, 14);
            const p1 = number.slice(0, 4);
            const p2 = number.slice(4, 8);
            const p3 = number.slice(8, 12);
            const p4 = number.slice(12, 14);
            return [p1, p2, p3, p4].filter(Boolean).join('-');
        }
    }" @open-modal-confirm.window="openModal($event)">

        <template x-teleport="body">
        <div x-show="open" style="display: none" x-on:keydown.escape.prevent.stop="open = false" role="dialog"
            aria-modal="true" x-id="['modal-title']" :aria-labelledby="$id('modal-title')"
            class="fixed inset-0 z-[100]">

            {{-- Overlay: full viewport, tidak ikut scroll --}}
            <div x-show="open" x-transition.opacity x-on:click="open = false"
                class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/70 backdrop-blur-md"></div>

            {{-- Panel wrapper: scrollable kalau modal lebih tinggi dari viewport --}}
            <div class="fixed inset-0 overflow-y-auto">
                <div x-show="open" x-transition
                    class="flex min-h-full items-center justify-center p-4">
                    <div x-trap.noscroll.inert="open"
                        class="relative w-full max-w-md glass-card gradient-border rounded-3xl p-6 sm:p-7 shadow-2xl shadow-slate-900/20">

                    <div class="flex items-start gap-3 mb-4">
                        <div class="size-10 shrink-0 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center shadow-md shadow-emerald-500/20">
                            <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold tracking-tight dark:text-white" :id="$id('modal-title')">
                                Konfirmasi Nomor WhatsApp
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                Kode OTP akan dikirimkan ke nomor ini. Pastikan nomor sudah benar.
                            </p>
                        </div>
                    </div>

                    <div class="my-5 rounded-2xl bg-white/40 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-700/40 px-5 py-4 text-center">
                        <p x-html="waNumber"
                            class="text-2xl font-bold tracking-wider text-emerald-600 dark:text-emerald-400"></p>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" x-on:click="open = false"
                            class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition">
                            Batal
                        </button>

                        <button type="button" x-on:click="open = false; $refs.submitBtn.click()"
                            class="relative inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white overflow-hidden group">
                            <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                            <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                            <span class="relative">Kirim OTP</span>
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </template>
    </div>
</div>
