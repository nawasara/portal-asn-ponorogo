<div>
    <div x-data="{ open: $wire.entangle('showModal') }">

        <template x-teleport="body">
            <div x-dialog x-model="open" x-cloak class="fixed inset-0 z-[100]">

                {{-- Overlay: full viewport --}}
                <div x-dialog:overlay x-transition.opacity
                    class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/70 backdrop-blur-md"></div>

                {{-- Panel wrapper: scrollable kalau panel lebih tinggi dari viewport --}}
                <div class="fixed inset-0 overflow-y-auto">
                    <div x-show="open" x-dialog:panel x-transition
                        class="flex min-h-full items-center justify-center p-4">
                        <div class="relative w-full max-w-md glass-card gradient-border rounded-3xl p-6 sm:p-8 shadow-2xl shadow-slate-900/20 text-center">

                            {{-- Icon --}}
                            <div class="mx-auto mb-5 size-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 grid place-items-center shadow-lg shadow-amber-500/30">
                                <svg class="size-7 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                            </div>

                            <h3 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">
                                Sesi SSO berakhir
                            </h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                                Anda telah logout dari Kisara SSO. Apakah Anda ingin melakukan login ulang?
                            </p>

                            <div class="mt-6 flex flex-col gap-2.5">
                                <a href="/login"
                                    class="relative inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white overflow-hidden group">
                                    <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                                    <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                                    <svg class="relative size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m10 17 5-5-5-5" />
                                        <path d="M15 12H3" />
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                    </svg>
                                    <span class="relative">Masuk Sekarang</span>
                                </a>

                                <button type="button" x-on:click="open = false"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 transition">
                                    Tidak, lain kali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
