<div>
    <section id="support" class="py-12 lg:py-16" data-aos="fade-up">

        @if (Route::is('supports'))
            <div class="flex items-center justify-center gap-x-3 md:hidden mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-slate-700 font-semibold">Bantuan SSO Kisara</span>
            </div>
        @endif

        <div class="text-center mb-10" data-aos="fade-up">
            <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-2">Pusat Bantuan</div>
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight dark:text-white">Mengalami kendala?</h2>
            <p class="mt-3 text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                Layanan mandiri untuk mereset MFA, mengajukan aduan, atau membaca panduan SSO.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Reset MFA --}}
            <a href="{{ route('mfa.reset') }}"
                class="tilt glass-card gradient-border rounded-3xl p-6 group relative block overflow-hidden"
                data-aos="zoom-in" data-aos-duration="1000">
                <div
                    class="absolute -bottom-16 -right-16 w-40 h-40 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 opacity-0 group-hover:opacity-20 blur-3xl transition duration-500">
                </div>

                <div
                    class="relative size-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center shadow-lg shadow-slate-900/10 mb-5">
                    <svg class="size-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>

                <h3 class="relative text-lg font-bold tracking-tight mb-2 dark:text-white">Reset MFA SSO</h3>
                <p class="relative text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-5">
                    Reset Multi-Factor Authentication Single Sign On Anda secara mandiri.
                </p>

                <div class="relative inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                    Reset MFA sekarang
                    <svg class="size-4 group-hover:translate-x-1 transition" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </div>
            </a>

            {{-- Formulir Aduan --}}
            <a href="https://asn.ponorogo.go.id/bantuan" target="_blank"
                class="tilt glass-card gradient-border rounded-3xl p-6 group relative block overflow-hidden"
                data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="100">
                <div
                    class="absolute -bottom-16 -right-16 w-40 h-40 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 opacity-0 group-hover:opacity-20 blur-3xl transition duration-500">
                </div>

                <div
                    class="relative size-12 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 grid place-items-center shadow-lg shadow-slate-900/10 mb-5">
                    <svg class="size-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        <path d="M10 9H8" />
                        <path d="M16 13H8" />
                        <path d="M16 17H8" />
                    </svg>
                </div>

                <h3 class="relative text-lg font-bold tracking-tight mb-2 dark:text-white">Formulir Aduan</h3>
                <p class="relative text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-5">
                    Isi formulir aduan, tim kami akan menghubungi Anda segera.
                </p>

                <div class="relative inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                    Isi formulir
                    <svg class="size-4 group-hover:translate-x-1 transition" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </div>
            </a>

            {{-- Panduan --}}
            <a href="{{ \App\Constants\Constants::DOC_URL }}" target="_blank"
                class="tilt glass-card gradient-border rounded-3xl p-6 group relative block overflow-hidden"
                data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="200">
                <div
                    class="absolute -bottom-16 -right-16 w-40 h-40 rounded-full bg-gradient-to-br from-violet-400 to-fuchsia-600 opacity-0 group-hover:opacity-20 blur-3xl transition duration-500">
                </div>

                <div
                    class="relative size-12 rounded-2xl bg-gradient-to-br from-violet-400 to-fuchsia-600 grid place-items-center shadow-lg shadow-slate-900/10 mb-5">
                    <svg class="size-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 7v14" />
                        <path d="M16 12h2" />
                        <path d="M16 8h2" />
                        <path
                            d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
                        <path d="M6 12h2" />
                        <path d="M6 8h2" />
                    </svg>
                </div>

                <h3 class="relative text-lg font-bold tracking-tight mb-2 dark:text-white">Panduan PDF</h3>
                <p class="relative text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-5">
                    Unduh panduan lengkap pengaktifan MFA SSO dalam format PDF.
                </p>

                <div class="relative inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                    Dapatkan panduan
                    <svg class="size-4 group-hover:translate-x-1 transition" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </div>
            </a>

        </div>
    </section>
</div>
