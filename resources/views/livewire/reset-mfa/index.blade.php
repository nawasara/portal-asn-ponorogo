<div class="min-h-dvh flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6">
    <div class="w-full max-w-3xl">

        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-black/5 dark:shadow-white/5 border border-gray-100 dark:border-gray-700 p-8">

            @livewire('reset-mfa.section.form')

            @livewire('reset-mfa.section.instruction')
        </div>
    </div>

    <livewire:shared-components.modal.session-modal />


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
