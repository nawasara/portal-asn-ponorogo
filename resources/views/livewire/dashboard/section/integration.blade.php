<div>
    <section id="integration" class="py-12 lg:py-16">

        @if (Route::is('integrations'))
            <div class="flex items-center justify-center gap-x-3 md:hidden mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Portal ASN Ponorogo" class="size-6 w-auto" loading="lazy" />
                <span class="dark:text-white text-slate-700 font-semibold">Integrasi SSO Kisara</span>
            </div>
        @endif

        <div class="glass-card gradient-border rounded-[2rem] p-8 md:p-12 relative overflow-hidden"
            data-aos="fade-up" data-aos-duration="1000">
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-emerald-400/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-sky-400/20 blur-3xl"></div>

            <div class="relative grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mb-3">Butuh Integrasi?</div>
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4 dark:text-white">
                        Sambungkan aplikasi Anda ke SSO Kisara
                    </h2>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        Kami membantu integrasi aplikasi OPD dengan Single Sign On via protokol OpenID Connect.
                        Cukup satu login untuk semua layanan — aman, cepat, dan terpusat.
                    </p>

                    <div class="flex flex-wrap gap-2 mb-8">
                        @foreach (['OIDC', 'OAuth 2.0', 'Keycloak', 'WhatsApp MFA', 'Session SSO'] as $chip)
                            <span class="text-xs font-semibold px-3 py-1.5 rounded-full glass-card">{{ $chip }}</span>
                        @endforeach
                    </div>

                    <a href="https://wa.me/{{ env('PIC_NUMBER', '') }}" target="_blank"
                        class="relative inline-flex items-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-semibold text-white overflow-hidden group w-fit">
                        <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                        <span
                            class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-xl opacity-40 group-hover:opacity-70 transition"></span>
                        <svg class="relative size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        <span class="relative">Hubungi Kami di WhatsApp</span>
                    </a>
                </div>

                {{-- SSO connection diagram --}}
                <div class="relative min-h-[18rem] hidden md:flex items-center justify-center">
                    <div class="relative glass-card rounded-2xl p-5 flex items-center gap-3 z-10">
                        <div class="size-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 grid place-items-center text-white font-bold shadow-lg shadow-emerald-500/30">
                            K
                        </div>
                        <div>
                            <div class="text-sm font-semibold dark:text-white">Kisara SSO</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400">Identity Provider</div>
                        </div>
                    </div>

                    @foreach ([
                        ['l' => 'S', 'g' => 'from-sky-400 to-blue-600', 'pos' => 'top-0 left-4'],
                        ['l' => 'R', 'g' => 'from-violet-400 to-fuchsia-600', 'pos' => 'top-4 right-0'],
                        ['l' => 'G', 'g' => 'from-amber-400 to-orange-600', 'pos' => 'bottom-4 left-0'],
                        ['l' => 'P', 'g' => 'from-rose-400 to-pink-600', 'pos' => 'bottom-0 right-4'],
                    ] as $sat)
                        <div class="absolute {{ $sat['pos'] }} glass-card rounded-xl p-2.5 animate-float"
                            style="animation-delay: {{ $loop->index * 0.8 }}s">
                            <div
                                class="size-8 rounded-lg bg-gradient-to-br {{ $sat['g'] }} grid place-items-center text-white text-xs font-bold">
                                {{ $sat['l'] }}
                            </div>
                        </div>
                    @endforeach

                    <svg class="absolute inset-0 w-full h-full pointer-events-none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="intLineGrad" x1="0" x2="1">
                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.5" />
                                <stop offset="100%" stop-color="#0ea5e9" stop-opacity="0.2" />
                            </linearGradient>
                        </defs>
                        <line x1="25%" y1="20%" x2="50%" y2="50%" stroke="url(#intLineGrad)" stroke-width="1.5"
                            stroke-dasharray="4 4" />
                        <line x1="85%" y1="25%" x2="50%" y2="50%" stroke="url(#intLineGrad)" stroke-width="1.5"
                            stroke-dasharray="4 4" />
                        <line x1="15%" y1="80%" x2="50%" y2="50%" stroke="url(#intLineGrad)" stroke-width="1.5"
                            stroke-dasharray="4 4" />
                        <line x1="85%" y1="90%" x2="50%" y2="50%" stroke="url(#intLineGrad)" stroke-width="1.5"
                            stroke-dasharray="4 4" />
                    </svg>
                </div>
            </div>
        </div>
    </section>
</div>
