<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6">
    <div class="w-full max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main form card -->
            <div class="md:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-md rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Perbarui Nomor WhatsApp</h2>

                    @if (session()->has('success'))
                        <div
                            class="mb-4 text-sm bg-green-50 dark:bg-green-800 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form Kirim OTP (update WA) --}}
                    @if (!$showOtpForm)
                        <form wire:submit.prevent="sendOtp" class="space-y-4" x-data="{ openTips: false }">
                            <label class="block">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Nomor WhatsApp</span>
                                <input type="text" inputmode="numeric" placeholder="0812-3456-7890"
                                    x-on:input="(() => {
                                        const el = $event.target;
                                        let raw = el.value.replace(/\D/g, '');
                                        if (raw.startsWith('62')) raw = '0' + raw.slice(2);
                                        if (!raw.startsWith('08')) {
                                            if (raw.startsWith('0')) raw = '08' + raw.slice(1);
                                            else raw = '08' + raw;
                                        }
                                        raw = raw.slice(0, 12); // limit to 12 digits (0 + 11)
                                        const p1 = raw.slice(0, 4);
                                        const p2 = raw.slice(4, 8);
                                        const p3 = raw.slice(8, 12);
                                        const formatted = p1 + (p2 ? '-' + p2 : '') + (p3 ? '-' + p3 : '');
                                        el.value = formatted;
                                        // update hidden raw input bound to Livewire
                                        if ($refs.whatsapp_raw) {
                                            $refs.whatsapp_raw.value = raw;
                                            $refs.whatsapp_raw.dispatchEvent(new Event('input'));
                                        }
                                    })()"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-900" />
                                <input type="hidden" x-ref="whatsapp_raw" wire:model.defer="whatsapp_number" />
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
                                    <span id="hs-soft-color-info-label" class="font-bold">Info</span> Nomor WhatsApp
                                    digunakan
                                    untuk
                                    verifikasi 2 langkah (MFA) demi keamanan akun Anda.
                                </div>
                            </template>

                            {{-- Komponen OTP (akan menampilkan form saat dikirim) --}}
                        </form>
                    @endif
                    <div class="mt-4">
                        <livewire:components.otp-form />
                    </div>
                </div>
            </div>

            <!-- Instruction card (separate) -->
            <div class="md:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-md rounded-2xl p-4 md:sticky md:top-6">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            i
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-100">Cara Verifikasi</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Ikuti langkah singkat berikut:
                            </p>
                        </div>
                    </div>

                    <ol class="mt-3 text-sm space-y-2 text-gray-700 dark:text-gray-200">
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">1.</span>
                            <div>Masukkan nomor WhatsApp Anda (format: 08xxxxxxxx)</div>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">2.</span>
                            <div>Klik <span class="font-medium">Kirim OTP</span>, kami akan mengirim kode 6 digit ke
                                nomor tersebut.
                            </div>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">3.</span>
                            <div>Masukkan kode OTP pada formulir <span class="font-medium">Masukkan OTP</span> untuk
                                menyelesaikan verifikasi.
                            </div>
                        </li>
                    </ol>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        Jika tidak menerima WA dalam beberapa menit, coba kirim ulang atau periksa kembali nomor
                        yang Anda masukkan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
