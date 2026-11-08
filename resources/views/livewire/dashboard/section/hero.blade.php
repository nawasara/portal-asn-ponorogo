<div>
    <section id="hero" class="grid items-center gap-10 py-14 lg:grid-cols-[1.15fr_.85fr] lg:py-20">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                <x-kisara-mark name="kisara-sso" class="size-4" />
                @auth Sesi Kisara SSO aktif @else Masuk sekali dengan Kisara SSO @endauth
            </span>

            <h1 class="mt-5 text-4xl font-semibold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl dark:text-white">
                @auth
                    Selamat datang,<br>{{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}.
                @else
                    Satu akun,<br>semua layanan ASN.
                @endauth
            </h1>

            <p class="mt-4 max-w-xl text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                @auth
                    Semua aplikasi di bawah langsung terbuka dengan sesi Anda — tanpa masuk lagi.
                @else
                    Buka seluruh aplikasi ASN Pemerintah Kabupaten Ponorogo dari satu tempat, dengan NIP dan kata sandi yang sama.
                @endauth
            </p>

            <div class="mt-7 flex flex-wrap gap-3">
                @auth
                    <a href="#app-lists" class="inline-flex h-12 items-center gap-2 rounded-full bg-ks-teal px-6 text-[15px] font-semibold text-white shadow-sm hover:bg-ks-teal-dark">
                        Buka aplikasi
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7" /></svg>
                    </a>
                @else
                    <a href="/login" class="inline-flex h-12 items-center gap-2 rounded-full bg-ks-teal px-6 text-[15px] font-semibold text-white shadow-sm hover:bg-ks-teal-dark">
                        Masuk dengan Kisara SSO
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 5l7 7-7 7" /></svg>
                    </a>
                @endauth
                <a href="{{ auth()->check() ? '#support' : '#app-lists' }}" @click="$dispatch('set-arrow');"
                    class="inline-flex h-12 items-center rounded-full border border-slate-300 bg-white px-6 text-[15px] font-semibold text-slate-800 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                    @auth Pusat bantuan @else Lihat aplikasi @endauth
                </a>
            </div>
        </div>

        @auth
            {{-- Ringkasan akun: tautan ke Akun Kisara, tempat semua pengaturan akun berada --}}
            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_24px_60px_-28px_rgb(15_23_42/.3)] dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center gap-4">
                    <span class="rounded-full p-[2px]" style="background:linear-gradient(100deg,#1f6fb2,#0f8b8d 40%,#2f9e6b 70%,#e0a82e)">
                        <span class="grid size-14 place-items-center rounded-full bg-ks-teal text-lg font-semibold text-white ring-4 ring-white dark:ring-slate-900">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </span>
                    <div class="min-w-0">
                        <div class="truncate text-lg font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</div>
                        <div class="truncate text-sm text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="mt-5 divide-y divide-slate-100 rounded-2xl border border-slate-200 text-sm dark:divide-slate-800 dark:border-slate-800">
                    @foreach ([
                        ['Keamanan & verifikasi dua langkah', account_url('/security')],
                        ['Passkey', account_url('/security/passkeys')],
                        ['Email dinas & layanan', account_url('/services')],
                    ] as [$label, $href])
                        <a href="{{ $href }}" class="flex items-center justify-between gap-3 px-4 py-3 text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800/60">
                            {{ $label }}
                            <svg class="size-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>
                        </a>
                    @endforeach
                </div>
                <a href="{{ account_url() }}" class="mt-4 inline-flex h-11 w-full items-center justify-center gap-2 rounded-full border border-slate-300 text-sm font-semibold text-slate-800 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800">
                    <x-kisara-mark name="akun-kisara" class="size-5" /> Kelola Akun Kisara
                </a>
            </div>
        @else
            <div class="relative hidden h-[360px] place-items-center lg:grid" aria-hidden="true">
                <div class="absolute size-[340px] rounded-full border border-dashed border-slate-300 dark:border-slate-700"></div>
                <div class="absolute size-[250px] rounded-full border border-slate-200 dark:border-slate-800"></div>
                <div class="absolute left-10 top-10 size-24 rounded-full bg-ks-blue/15 blur-2xl"></div>
                <div class="absolute bottom-8 right-12 size-28 rounded-full bg-ks-gold/20 blur-2xl"></div>
                <div class="absolute right-16 top-16 size-20 rounded-full bg-ks-green/15 blur-2xl"></div>
                <div class="relative grid size-44 place-items-center rounded-[40px] bg-white shadow-[0_24px_60px_-24px_rgb(15_23_42/.35)] ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                    <x-kisara-mark name="portal-asn" class="size-28" />
                </div>
            </div>
        @endauth
    </section>
</div>
