<div class="min-h-dvh flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6">
    <div class="w-full max-w-3xl">

        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-black/5 dark:shadow-white/5 border border-gray-100 dark:border-gray-700 p-8">

            <!-- FORM SECTION -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Reset MFA</h2>

                @if (session()->has('success'))
                    <div
                        class="mb-4 text-sm bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 p-3 rounded-md shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div
                        class="mb-4 text-sm bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 p-3 rounded-md shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form Kirim OTP --}}
                @if (!$showOtpForm && !$showBtnLoginForm)
                    <form wire:submit.prevent="sendOtp" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">NIP</label>
                            <input type="text" inputmode="numeric" placeholder="Masukkan NIP Anda untuk verifikasi"
                                x-mask="99999999 999999 9 999" x-init="(() => {
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
                                    raw = raw.slice(0,18);
                                    const p1 = raw.slice(0,8);
                                    const p2 = raw.slice(8,14);
                                    const p3 = raw.slice(14,15);
                                    const p4 = raw.slice(15,18);
                                    el.value = [p1,p2,p3,p4].filter(Boolean).join(' ');
                                    if ($refs.nip_raw) {
                                        $refs.nip_raw.value = raw;
                                        $refs.nip_raw.dispatchEvent(new Event('input'));
                                    }
                                })()"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 px-3 py-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500" />
                            <input type="hidden" x-ref="nip_raw" wire:model.defer="nip" />
                            @error('nip')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button wire:loading.remove type="submit"
                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 
                       text-white px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 
                       shadow-sm hover:shadow-md focus-visible:ring-2 focus-visible:ring-blue-500">
                                Simpan
                            </button>

                            <x-loading />

                            <a href="{{ url('/') }}"
                                class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                                Batalkan
                            </a>
                        </div>
                    </form>
                @endif

                <livewire:components.otp-form />

                @if ($showBtnLoginForm)
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex justify-center px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 
                       dark:bg-blue-500 dark:hover:bg-blue-600 text-white shadow-sm transition-all duration-200 
                       focus-visible:ring-2 focus-visible:ring-blue-500">
                                Kembali ke Halaman Login
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- INSTRUCTION SECTION -->
            <div class=" dark:bg-gray-800/60 rounded-xl p-6  dark:border dark:border-gray-700">
                <div class="flex items-center gap-2 mb-3">
                    <div
                        class="bg-blue-600 dark:bg-blue-500 text-white rounded-full w-7 h-7 flex items-center justify-center font-semibold text-sm">
                        i
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-300">Cara Reset MFA</h3>
                </div>

                <ol class="list-decimal list-inside text-gray-700 dark:text-gray-300 space-y-2 text-sm">
                    <li>Masukkan NIP Anda yang terdaftar.</li>
                    <li>Klik <b>Kirim OTP</b> untuk menerima kode verifikasi ke nomor WhatsApp Anda.</li>
                    <li>Periksa WhatsApp Anda dan pastikan kode OTP diterima.</li>
                    <li>Masukkan kode OTP dan klik <b>Verifikasi</b> untuk menyelesaikan proses reset MFA.</li>
                </ol>

                <p
                    class="mt-4 text-xs text-gray-500 dark:text-gray-400 border-t border-blue-100 dark:border-gray-700 pt-3">
                    Jika tidak menerima pesan dalam beberapa menit, coba kirim ulang atau hubungi admin jika masalah
                    berlanjut.
                </p>
            </div>

        </div>

    </div>

    <script>
        // Autofocus ke OTP setelah event 'otp-sent'
        window.addEventListener('otp-sent', () => {
            setTimeout(() => {
                const el = document.getElementById('otp-input');
                if (el) el.focus();
            }, 100);
        });
    </script>
</div>
