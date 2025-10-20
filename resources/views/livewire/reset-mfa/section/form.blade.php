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
    @if ($showForm)
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
                    class="block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" />
                <input type="hidden" x-ref="nip_raw" wire:model.defer="nip" />
                @error('nip')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div class="mt-2 flex justify-end">
                    <a href="http://asn.ponorogo.go.id/bantuan" target="_blank" rel="noopener"
                        class="text-xs text-emerald-600 hover:underline">
                        Butuh bantuan?
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button wire:loading.remove type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 
                       text-white px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 
                       shadow-sm hover:shadow-md focus-visible:ring-2 focus-visible:ring-emerald-500">
                    Kirim OTP
                </button>

                <x-loading />

                <a href="{{ url('/') }}"
                    class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Batalkan
                </a>
            </div>
        </form>
    @endif

    <livewire:shared-components.otp-form />
    <livewire:reset-mfa.section.button-logout />

</div>
