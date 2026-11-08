<div>
    <section id="integration" class="scroll-mt-24 py-12">
        @if (Route::is('integrations'))
            <div class="mb-6 flex items-center gap-2 md:hidden">
                <x-kisara-mark name="portal-asn" class="size-6" />
                <span class="font-semibold text-slate-700 dark:text-white">Integrasi Kisara SSO</span>
            </div>
        @endif

        <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <div class="ks-strip"></div>
            <div class="grid items-center gap-8 p-8 md:grid-cols-2 md:p-12">
                <div>
                    <span class="text-sm font-semibold text-ks-teal dark:text-teal-300">Untuk OPD</span>
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl dark:text-white">Sambungkan aplikasi Anda ke Kisara SSO</h2>
                    <p class="mt-3 leading-relaxed text-slate-600 dark:text-slate-400">Kami membantu integrasi aplikasi OPD lewat OpenID Connect. Satu kali masuk untuk semua layanan — aman, cepat, dan terpusat.</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach (['OpenID Connect', 'OAuth 2.0', 'Passkey', 'Verifikasi dua langkah'] as $chip)
                            <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-600 dark:border-slate-700 dark:text-slate-300">{{ $chip }}</span>
                        @endforeach
                    </div>
                    <a href="https://wa.me/{{ env('PIC_NUMBER', '') }}" target="_blank" rel="noopener noreferrer"
                        class="mt-6 inline-flex h-11 items-center gap-2 rounded-full bg-ks-teal px-5 text-sm font-semibold text-white hover:bg-ks-teal-dark">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" /></svg>
                        Hubungi kami di WhatsApp
                    </a>
                </div>
                <div class="relative mx-auto grid size-72 place-items-center" aria-hidden="true">
                    <div class="absolute inset-6 rounded-full border border-dashed border-slate-300 dark:border-slate-700"></div>
                    <div class="grid size-24 place-items-center rounded-3xl bg-white shadow-[0_16px_40px_-18px_rgb(15_23_42/.4)] ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                        <x-kisara-mark name="kisara-sso" class="size-14" />
                    </div>
                    @foreach ([
                        ['akun-kisara', 'top:0;left:50%;transform:translateX(-50%)'],
                        ['kisara-mail', 'top:22%;right:0'],
                        ['nawasara', 'bottom:22%;right:0'],
                        ['gasta', 'bottom:0;left:50%;transform:translateX(-50%)'],
                        ['portal-asn', 'bottom:22%;left:0'],
                    ] as [$mark, $pos])
                        <div class="absolute grid size-14 place-items-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700" style="{{ $pos }}">
                            <x-kisara-mark :name="$mark" class="size-8" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
