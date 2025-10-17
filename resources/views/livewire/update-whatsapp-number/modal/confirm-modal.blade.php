<div>
    {{-- In work, do what you enjoy. --}}
    <div x-data="{
        open: false,
        waNumber: '',
        openModal(e) {
            this.waNumber = e.detail.whatsappNumber ?? '0';
            this.open = true;
        }
    }" @open-modal-confirm.window="openModal($event)" class="flex justify-center">
        <!-- Modal -->
        <div x-show="open" style="display: none" x-on:keydown.escape.prevent.stop="open = false" role="dialog"
            aria-modal="true" x-id="['modal-title']" :aria-labelledby="$id('modal-title')"
            class="fixed inset-0 z-10 overflow-y-auto ">
            <!-- Overlay -->
            <div x-show="open" x-transition.opacity
                class="fixed inset-0 bg-black/25 dark:bg-black/50 backdrop-blur-md"></div>

            <!-- Panel -->
            <div x-show="open" x-transition x-on:click="open = false"
                class="relative flex min-h-screen items-center justify-center p-4">
                <div x-on:click.stop x-trap.noscroll.inert="open"
                    class="relative min-w-96 max-w-xl rounded-xl bg-white dark:bg-gray-800 p-6 shadow-lg">
                    <!-- Title -->
                    <h2 class="font-medium text-gray-800 dark:text-gray-100" :id="$id('modal-title')">Konfirmasi</h2>

                    <!-- Content -->
                    <p class="mt-2 text-gray-500 dark:text-gray-300 max-w-xs">Kode OTP akan dikirimkan ke Nomor WhatsApp
                        ini. Silahkan
                        periksa kembali jika ada kesalahan!</p>
                    <p x-html="waNumber" class="mt-2 text-emerald-500 dark:text-emerald-400 text-2xl max-w-xs"></p>

                    <!-- Buttons -->
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" x-on:click="open = false"
                            class="relative flex items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-transparent bg-transparent px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-800/10 dark:hover:bg-white/5">
                            Batal
                        </button>

                        <button type="button" x-on:click="open = false; $refs.submitBtn.click()"
                            class="relative flex items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-transparent bg-gray-800 dark:bg-emerald-600 px-4 py-2 text-white hover:bg-gray-900 dark:hover:bg-emerald-700">
                            Kirim OTP
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
