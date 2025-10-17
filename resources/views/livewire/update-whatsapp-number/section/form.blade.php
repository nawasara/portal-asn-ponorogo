<div>
    @if (!$showOtpForm)
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Perbarui Nomor WhatsApp</h2>

        <form wire:submit.prevent="sendOtp" class="space-y-5" x-data="{ openTips: false, whatsappNumber: '' }">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Nomor WhatsApp
                </label>
                <input type="text" inputmode="numeric" placeholder="0812-3456-7890"
                    x-on:input="(() => {
                    const el = $event.target;
                    let raw = el.value.replace(/\D/g, ''); // hapus semua karakter non-digit

                    // Normalisasi prefix
                    if (raw.startsWith('62')) raw = '0' + raw.slice(2);
                    if (!raw.startsWith('08')) {
                        if (raw.startsWith('0')) raw = '08' + raw.slice(1);
                        else raw = '08' + raw;
                    }

                    // Batasi maksimal 14 digit
                    raw = raw.slice(0, 14);

                    // Format ke dalam grup 4-4-4-2 (sesuai panjang nomor)
                    const p1 = raw.slice(0, 4);
                    const p2 = raw.slice(4, 8);
                    const p3 = raw.slice(8, 12);
                    const p4 = raw.slice(12, 14);

                    const formatted = [p1, p2, p3, p4].filter(Boolean).join('-');

                    el.value = formatted;

                    // Simpan nilai raw (tanpa format) ke property untuk dikirim
                    if ($refs.whatsapp_raw) {
                        $refs.whatsapp_raw.value = raw;
                        $refs.whatsapp_raw.dispatchEvent(new Event('input'));
                    }

                    // update local x-model
                    whatsappNumber = raw;
                })()"
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" />
                <input type="hidden" x-ref="whatsapp_raw" x-model="whatsappNumber"
                    wire:model.defer="whatsapp_number" />
                @error('whatsapp_number')
                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <button type="button"
                    @click.prevent="$dispatch('open-modal-confirm', { whatsappNumber: whatsappNumber })"
                    class="inline-flex items-center px-5 py-2 rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800 transition">
                    Kirim OTP
                </button>
                <x-loading />

                <button wire:loading.remove type="submit" x-ref="submitBtn" class="hidden">
                    Do
                </button>

                <a @click="openTips = !openTips"
                    class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:underline">
                    Mengapa perlu WhatsApp?
                </a>
            </div>

            <template x-if="openTips">
                <div
                    class="mt-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-800 dark:text-emerald-300 rounded-lg p-4">
                    <span class="font-semibold">Info:</span> Nomor WhatsApp digunakan untuk verifikasi dua
                    langkah (MFA) demi keamanan akun Anda.
                </div>
            </template>

            <livewire:update-whatsapp-number.modal.confirm-modal />
        </form>
    @endif
</div>
