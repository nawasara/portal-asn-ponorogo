<div>
    <footer class="hidden md:block pt-8 pb-10 border-t border-slate-200 dark:border-slate-800 mt-8">
        <div class="flex items-center justify-between gap-4 text-sm">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <div>
                    <div class="font-semibold dark:text-white">Portal ASN Ponorogo</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        Dikembangkan
                        <a href="https://kominfo.ponorogo.go.id"
                            class="hover:text-emerald-600 dark:hover:text-emerald-400 transition">Dinas Kominfo dan Statistik</a>
                    </div>
                </div>
            </div>
            <div class="text-xs text-slate-500 dark:text-slate-400 text-right">
                <div>© {{ date('Y') }} Pemerintah Kabupaten Ponorogo</div>
                <div class="mt-1">Tim Pengembang A4 Bidang APTIKA</div>
            </div>
        </div>
    </footer>

    {{-- Mobile footer (compact) --}}
    <footer class="md:hidden py-10 mt-6">
        <div class="glass-card rounded-2xl p-6 text-center">
            <div class="flex items-center justify-center gap-2.5 mb-3">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <div class="text-sm font-semibold dark:text-white">Portal ASN Ponorogo</div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                Dikembangkan oleh
                <a href="https://kominfo.ponorogo.go.id"
                    class="text-emerald-600 dark:text-emerald-400 font-semibold">Dinas Kominfo dan Statistik Kabupaten Ponorogo</a>
            </p>
            <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-0.5">
                <div>© {{ date('Y') }} Pemerintah Kabupaten Ponorogo</div>
                <div>Tim Pengembang A4 Bidang APTIKA</div>
            </div>
        </div>
    </footer>
</div>
