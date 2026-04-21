<div>
    <div class="flex items-center gap-3 mb-6">
        <div class="size-11 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center shadow-lg shadow-emerald-500/20">
            <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold tracking-tight dark:text-white">Reset MFA</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Reset Multi-Factor Authentication secara mandiri</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 text-sm bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/60 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 p-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 text-sm bg-rose-50/80 dark:bg-rose-500/10 border border-rose-200/60 dark:border-rose-500/30 text-rose-700 dark:text-rose-300 p-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    @if ($showForm)
        <form wire:submit.prevent="sendOtp" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">NIP</label>
                <input type="text" inputmode="numeric" placeholder="Masukkan NIP Anda untuk verifikasi"
                    x-mask="99999999 999999 9 999"
                    x-init="(() => {
                        const raw = ($refs.nip_raw && $refs.nip_raw.value) ? $refs.nip_raw.value.replace(/\D/g, '').slice(0, 18) : '';
                        const p1 = raw.slice(0, 8),
                              p2 = raw.slice(8, 14),
                              p3 = raw.slice(14, 15),
                              p4 = raw.slice(15, 18);
                        $el.value = [p1, p2, p3, p4].filter(Boolean).join(' ');
                    })()"
                    x-on:input="(() => {
                        const el = $event.target;
                        let raw = el.value.replace(/\D/g, '');
                        raw = raw.slice(0, 18);
                        const p1 = raw.slice(0, 8);
                        const p2 = raw.slice(8, 14);
                        const p3 = raw.slice(14, 15);
                        const p4 = raw.slice(15, 18);
                        el.value = [p1, p2, p3, p4].filter(Boolean).join(' ');
                        if ($refs.nip_raw) {
                            $refs.nip_raw.value = raw;
                            $refs.nip_raw.dispatchEvent(new Event('input'));
                        }
                    })()"
                    class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/40 backdrop-blur-sm text-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" />
                <input type="hidden" x-ref="nip_raw" wire:model.defer="nip" />
                @error('nip')
                    <p class="text-rose-600 dark:text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                @enderror
                <div class="mt-2 flex justify-end">
                    <a href="http://asn.ponorogo.go.id/bantuan" target="_blank" rel="noopener"
                        class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-medium">
                        Butuh bantuan?
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <button wire:loading.remove type="submit"
                    class="relative inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white overflow-hidden group">
                    <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                    <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                    <span class="relative">Kirim OTP</span>
                </button>

                <x-loading />

                <a href="{{ url('/') }}"
                    class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
                    Batalkan
                </a>
            </div>
        </form>
    @endif

    <livewire:shared-components.otp-form />
    <livewire:reset-mfa.section.button-logout />
</div>
