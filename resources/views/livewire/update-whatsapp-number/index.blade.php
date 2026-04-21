<div class="relative min-h-dvh flex items-center justify-center p-4 sm:p-6">
    <x-aurora />

    <div class="w-full max-w-4xl">
        <div class="glass-card gradient-border rounded-3xl p-6 sm:p-8 md:p-10 shadow-xl shadow-slate-900/5">
            <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-8">
                <div>
                    @if (session()->has('success'))
                        <div class="mb-4 text-sm bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/60 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 p-3 rounded-xl">
                            {{ session('success') }}
                        </div>
                    @endif

                    <livewire:update-whatsapp-number.section.form />
                    <livewire:shared-components.otp-form />
                </div>

                <livewire:update-whatsapp-number.section.instruction />
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="/" class="text-xs text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                ← Kembali ke Portal
            </a>
        </div>
    </div>

    <livewire:shared-components.modal.session-modal />
</div>
