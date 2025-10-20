<div class="min-h-dvh flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6">
    <div class="w-full max-w-3xl">
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-black/5 dark:shadow-white/5 border border-gray-100 dark:border-gray-700 p-8">

            <div>
                @if (session()->has('success'))
                    <div
                        class="mb-4 text-sm bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 p-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- whatsapp number form section --}}
                <livewire:update-whatsapp-number.section.form />

                {{-- OTP form --}}
                <livewire:shared-components.otp-form />
            </div>

            {{-- intructions section --}}
            <livewire:update-whatsapp-number.section.instruction />

        </div>
    </div>
    <livewire:shared-components.modal.session-modal />

</div>
