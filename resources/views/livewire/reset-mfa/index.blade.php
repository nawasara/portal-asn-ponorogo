<div class="relative min-h-dvh flex items-center justify-center p-4 sm:p-6">
    <x-aurora />

    <div class="w-full max-w-4xl">
        <div class="glass-card gradient-border rounded-3xl p-6 sm:p-8 md:p-10 shadow-xl shadow-slate-900/5">
            <div class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-8">
                @livewire('reset-mfa.section.form')
                @livewire('reset-mfa.section.instruction')
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="/" class="text-xs text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                ← Kembali ke Portal
            </a>
        </div>
    </div>

    <livewire:shared-components.modal.session-modal />

    <script>
        window.addEventListener('otp-sent', () => {
            setTimeout(() => {
                const el = document.getElementById('otp-input');
                if (el) el.focus();
            }, 100);
        });
    </script>
</div>
