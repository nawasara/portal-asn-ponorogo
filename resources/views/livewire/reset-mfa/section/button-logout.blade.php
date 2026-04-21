<div>
    @if ($showLogout)
        <div class="mt-6 pt-5 border-t border-slate-200/60 dark:border-slate-700/40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="relative w-full inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white overflow-hidden group">
                    <span class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600"></span>
                    <span class="absolute -inset-1 bg-gradient-to-br from-emerald-400 to-sky-500 blur-lg opacity-40 group-hover:opacity-70 transition"></span>
                    <svg class="relative size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 5l7 7-7 7" />
                    </svg>
                    <span class="relative">Kembali ke Halaman Login</span>
                </button>
            </form>
        </div>
    @endif
</div>
