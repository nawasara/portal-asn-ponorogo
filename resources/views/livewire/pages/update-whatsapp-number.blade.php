<div class="min-h-screen flex items-center justify-center bg-gray-50 p-6 dark:bg-gray-900">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h2 class="text-xl font-semibold mb-4">Perbarui Nomor WhatsApp</h2>

            @if (session()->has('success'))
                <div class="mb-4 text-sm bg-green-50 border border-green-200 text-green-700 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Kirim OTP --}}
            @if (!$showOtpForm)
                <form wire:submit.prevent="sendOtp" class="space-y-4" x-data="{ openTips: false }">
                    <label class="block">
                        <span class="text-sm font-medium">Nomor WhatsApp</span>
                        <input wire:model.defer="whatsapp_number" type="text" inputmode="numeric"
                            placeholder="6281234567890"
                            class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring" />
                    </label>
                    @error('whatsapp_number')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror

                    <div class="flex items-center gap-2">
                        <button wire:loading.remove type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium border hover:bg-gray-50">
                            Kirim OTP
                        </button>

                        <x-loading />

                        <a @click="openTips = !openTips"
                            class="text-sm text-gray-600 cursor-pointer hover:underline">Pelajari kenapa kami
                            perlu WhatsApp Anda!</a>
                    </div>
                    <template x-if="openTips">
                        <div class="mt-2 bg-blue-100 border border-blue-200 text-sm text-blue-800 rounded-lg p-4 dark:bg-blue-800/10 dark:border-blue-900 dark:text-blue-500"
                            role="alert" tabindex="-1" aria-labelledby="hs-soft-color-info-label">
                            <span id="hs-soft-color-info-label" class="font-bold">Info</span> Nomor WhatsApp digunakan
                            untuk
                            verifikasi 2 langkah (MFA) demi keamanan akun Anda.
                        </div>
                    </template>
                </form>
            @endif

            {{-- OTP Form --}}
            @if ($showOtpForm)
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-sm font-medium mb-2">Masukkan OTP</h3>
                    <form wire:submit.prevent="verifyOtp" class="space-y-3">
                        <input wire:model.defer="otp" type="text" inputmode="numeric" maxlength="6"
                            placeholder="6 digit OTP" id="otp-input"
                            class="block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring" />
                        @error('otp')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror

                        <div class="flex items-center gap-2">

                            <button type="submit"
                                class="px-4 py-2 rounded-md border text-sm font-medium">Verifikasi</button>

                            <button type="button" wire:click="resendOtp" class="px-3 py-2 rounded-md border text-sm">
                                Kirim Ulang
                            </button>
                            <x-loading />

                            <span class="text-xs text-gray-500 ml-auto">OTP berlaku {{ $otpTtlMinutes }} menit</span>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        // autofocus ke OTP setelah event 'otp-sent'
        window.addEventListener('otp-sent', () => {
            setTimeout(() => {
                const el = document.getElementById('otp-input');
                if (el) el.focus();
            }, 100);
        });
    </script>
</div>
