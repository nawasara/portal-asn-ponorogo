<div>
    @if (!$showOtpForm)
        <div class="flex items-center gap-3 mb-6">
            <div class="size-11 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center shadow-lg shadow-emerald-500/20">
                <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold tracking-tight dark:text-white">Perbarui Nomor WhatsApp</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Daftarkan nomor untuk menerima OTP</p>
            </div>
        </div>

        <form wire:submit.prevent="sendOtp" class="space-y-5"
            x-data="{ openTips: false, whatsappNumber: '' }"
            @keydown.enter.prevent="$dispatch('open-modal-confirm', { whatsappNumber: whatsappNumber })">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                    Nomor WhatsApp
                </label>
                <input type="text" inputmode="numeric" placeholder="0812-3456-7890"
                    x-on:input="(() => {
                        const el = $event.target;
                        let raw = el.value.replace(/\D/g, '');

                        if (raw.startsWith('62')) raw = '0' + raw.slice(2);
                        if (!raw.startsWith('08')) {
                            if (raw.startsWith('0')) raw = '08' + raw.slice(1);
                            else raw = '08' + raw;
                        }

                        raw = raw.slice(0, 14);

                        const p1 = raw.slice(0, 4);
                        const p2 = raw.slice(4, 8);
                        const p3 = raw.slice(8, 12);
                        const p4 = raw.slice(12, 14);

                        const formatted = [p1, p2, p3, p4].filter(Boolean).join('-');
                        el.value = formatted;

                        if ($refs.whatsapp_raw) {
                            $refs.whatsapp_raw.value = raw;
                            $refs.whatsapp_raw.dispatchEvent(new Event('input'));
                        }

                        whatsappNumber = raw;
                    })()"
                    class="block w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/40 backdrop-blur-sm text-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" />
                <input type="hidden" x-ref="whatsapp_raw" x-model="whatsappNumber"
                    wire:model.defer="whatsapp_number" />
                @error('whatsapp_number')
                    <div class="text-rose-600 dark:text-rose-400 text-sm mt-1.5">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <button type="button"
                    @click.prevent="$dispatch('open-modal-confirm', { whatsappNumber: whatsappNumber })"
                    class="relative inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white overflow-hidden group">
                    <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                    <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                    <span class="relative">Kirim OTP</span>
                </button>

                <x-loading />

                <button wire:loading.remove type="submit" x-ref="submitBtn" class="hidden">Do</button>

                <button type="button" @click="openTips = !openTips"
                    class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-pointer underline-offset-4 hover:underline transition">
                    Mengapa perlu WhatsApp?
                </button>
            </div>

            <template x-if="openTips">
                <div class="mt-3 text-sm bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/60 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-300 rounded-xl p-4 backdrop-blur-sm">
                    <span class="font-semibold">Info:</span> Nomor WhatsApp digunakan untuk verifikasi dua langkah (MFA) demi keamanan akun Anda.
                </div>
            </template>

            <livewire:update-whatsapp-number.modal.confirm-modal />
        </form>
    @endif
</div>
