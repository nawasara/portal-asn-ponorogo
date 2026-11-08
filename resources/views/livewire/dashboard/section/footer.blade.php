<div>
    <footer class="mt-10 border-t border-slate-200 dark:border-slate-800">
        <div class="py-10">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Ekosistem Kisara</div>
            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ([
                    ['kisara-sso', 'Kisara SSO', 'Satu akun, semua layanan'],
                    ['akun-kisara', 'Akun Kisara', 'Kelola akun Anda'],
                    ['kisara-mail', 'Kisara Mail', 'Email dinas ASN'],
                    ['portal-asn', 'Portal ASN', 'Layanan ASN terpadu'],
                    ['nawasara', 'Nawasara', 'Konsol layanan digital'],
                    ['gasta', 'Gasta', 'Pantauan CCTV terpadu'],
                ] as [$mark, $name, $tagline])
                    <div class="flex items-center gap-2.5">
                        <x-kisara-mark :name="$mark" class="size-8 shrink-0" />
                        <div class="min-w-0 leading-tight">
                            <div class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $name }}</div>
                            <div class="truncate text-xs text-slate-500">{{ $tagline }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-slate-200 pt-6 text-xs text-slate-500 dark:border-slate-800">
                <span>© {{ date('Y') }} Pemerintah Kabupaten Ponorogo</span>
                <span>Dikembangkan <a href="https://kominfo.ponorogo.go.id" class="hover:text-ks-teal dark:hover:text-teal-300">Dinas Komunikasi, Informatika dan Statistik</a> — Tim A4 Bidang APTIKA</span>
            </div>
        </div>
    </footer>
</div>
