<div>
    @if ($showForm)
        @if ($infoMessage && $infoMessageType == 'success')
            <div
                class="mb-4 text-sm bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 p-3 rounded">
                {{ $infoMessage }}
            </div>
        @endif
        @if ($infoMessage && $infoMessageType == 'error')
            <div
                class="mb-4 text-sm bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 p-3 rounded">
                {{ $infoMessage }}
            </div>
        @endif
        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
            <h3 class="text-sm font-medium mb-2 text-gray-700 dark:text-gray-200">{{ $label }}</h3>
            <form wire:submit.prevent="verifyOtp" class="space-y-3">
                <input wire:model.defer="otp" type="text" inputmode="numeric" maxlength="6" placeholder="6 digit OTP"
                    id="otp-input"
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" />
                @error('otp')
                    <div class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</div>
                @enderror
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800 transition">Verifikasi</button>
                    @if ($showResend)
                        <button type="button" wire:click="resendOtp"
                            class="px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-100 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">Kirim
                            Ulang</button>
                    @endif
                    <x-loading />
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-auto">OTP berlaku {{ $otpTtlMinutes }}
                        menit</span>
                </div>
            </form>
        </div>
    @endif
</div>
