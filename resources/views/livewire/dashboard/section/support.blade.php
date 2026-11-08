<div>
    <section id="support" class="scroll-mt-24 py-12">
        @if (Route::is('supports'))
            <div class="mb-6 flex items-center gap-2 md:hidden">
                <x-kisara-mark name="portal-asn" class="size-6" />
                <span class="font-semibold text-slate-700 dark:text-white">Bantuan Kisara SSO</span>
            </div>
        @endif

        <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl dark:text-white">Butuh bantuan?</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Layanan mandiri untuk mereset MFA, mengajukan aduan, atau membaca panduan.</p>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @foreach ([
                ['href' => account_url('/reset-mfa'), 'color' => '#0f8b8d', 'title' => 'Reset MFA', 'text' => 'Atur ulang verifikasi dua langkah Anda secara mandiri lewat Akun Kisara.', 'cta' => 'Reset MFA', 'blank' => false,
                 'icon' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>'],
                ['href' => 'https://asn.ponorogo.go.id/bantuan', 'color' => '#1f6fb2', 'title' => 'Formulir aduan', 'text' => 'Ceritakan kendala Anda; tim kami akan menghubungi Anda.', 'cta' => 'Isi formulir', 'blank' => true,
                 'icon' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4M9 13h6M9 17h6"/>'],
                ['href' => \App\Constants\Constants::DOC_URL, 'color' => '#e0a82e', 'title' => 'Panduan Kisara SSO', 'text' => 'Panduan lengkap masuk, passkey, dan verifikasi dua langkah.', 'cta' => 'Buka panduan', 'blank' => true,
                 'icon' => '<path d="M12 7v14M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>'],
            ] as $card)
                <a href="{{ $card['href'] }}" @if ($card['blank']) target="_blank" rel="noopener noreferrer" @endif
                    class="group rounded-3xl border border-slate-200 bg-white p-6 transition hover:border-ks-teal/40 hover:ks-card-shadow dark:border-slate-800 dark:bg-slate-900">
                    <span class="grid size-11 place-items-center rounded-2xl text-white" style="background: {{ $card['color'] }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $card['icon'] !!}</svg>
                    </span>
                    <h3 class="mt-4 text-[17px] font-semibold text-slate-900 dark:text-white">{{ $card['title'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $card['text'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-ks-teal dark:text-teal-300">
                        {{ $card['cta'] }}
                        <svg class="size-4 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7" /></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
</div>
