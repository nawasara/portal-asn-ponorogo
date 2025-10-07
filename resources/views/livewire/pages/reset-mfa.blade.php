<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6">
    <div class="w-full max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main card (form + OTP) -->
            <div class="md:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-md rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Reset MFA</h2>

                    @if (session()->has('success'))
                        <div
                            class="mb-4 text-sm bg-green-50 dark:bg-green-800 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div
                            class="mb-4 text-sm bg-red-50 dark:bg-red-800 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 p-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Form Kirim OTP --}}
                    @if (!$showOtpForm && !$showBtnLoginForm)
                        <form wire:submit.prevent="sendOtp" class="space-y-4">
                            <label class="block">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">NIP</span>
                                <input wire:model.defer="nip" type="text" inputmode="numeric"
                                    placeholder="Masukkan NIP Anda untuk verifikasi" maxlength="18"
                                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200 dark:focus:ring-blue-900" />
                            </label>
                            @error('nip')
                                <div class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</div>
                            @enderror

                            <div class="flex items-center gap-2">
                                <button wire:loading.remove type="submit"
                                    class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-100 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">Kirim
                                    OTP</button>

                                <x-loading />

                                <a href="{{ url('/') }}"
                                    class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:underline">Batalkan</a>
                            </div>
                        </form>
                    @endif

                    <livewire:components.otp-form />

                    @if ($showBtnLoginForm)
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow-sm">Kembali
                                    ke Halaman Login</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Instruction card -->
            <div class="md:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-md rounded-2xl p-4 md:sticky md:top-6">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            i</div>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-100">Cara Reset MFA</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Ikuti langkah singkat berikut:</p>
                        </div>
                    </div>

                    <ol class="mt-3 text-sm space-y-2 text-gray-700 dark:text-gray-200">
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">1.</span>
                            <div>Masukkan NIP Anda yang terdaftar.</div>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">2.</span>
                            <div>Klik <span class="font-medium">Kirim OTP</span> untuk menerima kode verifikasi yang
                                dikirim ke nomor WhatsApp Anda.</div>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">3.</span>
                            <div>Periksa WhatsApp Anda dan pastikan Anda menerima kode OTP yang sudah dikirim.</div>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-semibold text-blue-600">4.</span>
                            <div>Masukkan kode OTP pada formulir dan Klik <span class="font-medium">Verifikasi</span>
                                untuk
                                menyelesaikan proses reset MFA.</div>
                        </li>
                    </ol>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        Jika tidak menerima pesan dalam beberapa menit, coba kirim ulang atau hubungi admin jika
                        masalah berlanjut.
                    </div>
                </div>
            </div>
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
