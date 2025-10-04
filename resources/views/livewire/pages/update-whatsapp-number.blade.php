<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-900 shadow-md rounded-2xl p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Perbarui Nomor WhatsApp</h2>

            @if (session()->has('success'))
                <div
                    class="mb-4 text-sm bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (!$showOtpForm)
                {{-- Form Kirim OTP --}}
                <form wire:submit.prevent="sendOtp" class="space-y-4" x-data="{ openTips: false }">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Nomor WhatsApp</span>
                        <input wire:model.defer="whatsapp_number" type="text" inputmode="numeric"
                            placeholder="6281234567890"
                            class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-900" />
                    </label>
                    @error('whatsapp_number')
                        <div class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</div>
                    @enderror

                    <div class="flex items-center gap-2">
                        <button wire:loading.remove type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-100 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">Kirim
                            OTP</button>

                        <x-loading />

                        <a @click="openTips = !openTips"
                            class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:underline">Pelajari
                            kenapa kami
                            perlu WhatsApp Anda!</a>
                    </div>
                    <template x-if="openTips">
                        <div class="mt-2 bg-blue-100 dark:bg-blue-900/40 border border-blue-200 dark:border-blue-900 text-sm text-blue-800 dark:text-blue-300 rounded-lg p-4"
                            role="alert" tabindex="-1" aria-labelledby="hs-soft-color-info-label">
                            <span id="hs-soft-color-info-label" class="font-bold">Info</span> Nomor WhatsApp digunakan
                            untuk
                            verifikasi 2 langkah (MFA) demi keamanan akun Anda.
                        </div>
                    </template>
                </form>
            @endif

            <livewire:components.otp-form />
        </div>
    </div>
</div>
