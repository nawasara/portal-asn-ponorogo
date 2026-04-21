<div>
    @if ($showForm)
        @if ($infoMessage && $infoMessageType == 'success')
            <div class="mb-4 text-sm bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/60 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 p-3 rounded-xl backdrop-blur-sm">
                {{ $infoMessage }}
            </div>
        @endif
        @if ($infoMessage && $infoMessageType == 'error')
            <div class="mb-4 text-sm bg-rose-50/80 dark:bg-rose-500/10 border border-rose-200/60 dark:border-rose-500/30 text-rose-700 dark:text-rose-300 p-3 rounded-xl backdrop-blur-sm">
                {{ $infoMessage }}
            </div>
        @endif

        <div class="mt-6 pt-5 border-t border-slate-200/60 dark:border-slate-700/40">
            <h3 class="text-sm font-semibold mb-3 text-slate-700 dark:text-slate-200">{{ $label }}</h3>
            <form wire:submit.prevent="verifyOtp" class="space-y-4">
                <input wire:model.defer="otp" type="text" inputmode="numeric" maxlength="6"
                    placeholder="6 digit OTP" id="otp-input" autocomplete="one-time-code"
                    class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/40 backdrop-blur-sm text-slate-900 dark:text-slate-100 px-4 py-3 text-lg tracking-[0.5em] text-center font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" />
                @error('otp')
                    <div class="text-rose-600 dark:text-rose-400 text-sm">{{ $message }}</div>
                @enderror

                <div class="flex items-center gap-2 flex-wrap">
                    <button type="submit"
                        class="relative inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white overflow-hidden group">
                        <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                        <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                        <span class="relative">Verifikasi</span>
                    </button>

                    @if ($showResend)
                        <button type="button" wire:click="resendOtp"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-200 bg-white/60 dark:bg-slate-900/40 hover:bg-white dark:hover:bg-slate-800 backdrop-blur-sm transition">
                            Kirim Ulang
                        </button>
                    @endif

                    <x-loading />

                    <span class="text-xs text-slate-500 dark:text-slate-400 ml-auto">
                        OTP berlaku {{ $otpTtlMinutes }} menit
                    </span>
                </div>
            </form>
        </div>
    @endif
</div>
