<div>
    <section id="faq" class="scroll-mt-24 py-12">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl dark:text-white">Pertanyaan umum</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tidak menemukan jawaban? <a href="#support" class="font-semibold text-ks-teal dark:text-teal-300">Hubungi bantuan</a>.</p>
            </div>
            <div class="space-y-3">
                @foreach ($this->availableFaq() as $index => $faq)
                    <details class="group rounded-2xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900" wire:key="{{ $faq['id'] }}" {{ $index === 0 ? 'open' : '' }}>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 font-semibold text-slate-900 hover:text-ks-teal dark:text-white dark:hover:text-teal-300">
                            {{ $faq['title'] }}
                            <span class="grid size-8 shrink-0 place-items-center rounded-full bg-slate-100 transition group-open:rotate-180 dark:bg-slate-800">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                            </span>
                        </summary>
                        <div class="px-5 pb-5 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{!! $faq['description'] !!}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
</div>
