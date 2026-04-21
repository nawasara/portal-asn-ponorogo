<div class="glass-card rounded-2xl p-6">
    <div class="flex items-center gap-2.5 mb-4">
        <div class="size-8 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 grid place-items-center text-white font-bold text-sm shadow-md shadow-emerald-500/20">
            i
        </div>
        <h3 class="text-base font-bold tracking-tight text-slate-800 dark:text-white">
            Cara Verifikasi WhatsApp
        </h3>
    </div>

    <ol class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
        @foreach ([
            'Masukkan nomor WhatsApp Anda dengan format benar (contoh: 08xxxxxxxxxx).',
            'Klik <b>Kirim OTP</b> untuk menerima kode verifikasi 6 digit via WhatsApp.',
            'Masukkan kode OTP di kolom verifikasi untuk menyelesaikan proses.',
            'Jika belum menerima OTP, klik <b>Kirim Ulang</b>.',
        ] as $i => $step)
            <li class="flex gap-3">
                <span class="shrink-0 size-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-xs font-bold grid place-items-center">
                    {{ $i + 1 }}
                </span>
                <span class="leading-relaxed pt-0.5">{!! $step !!}</span>
            </li>
        @endforeach
    </ol>

    <p class="mt-5 pt-4 text-xs text-slate-500 dark:text-slate-400 border-t border-slate-200/60 dark:border-slate-700/40 leading-relaxed">
        Jika tidak menerima pesan dalam beberapa menit, coba kirim ulang atau hubungi admin jika masalah berlanjut.
    </p>
</div>
