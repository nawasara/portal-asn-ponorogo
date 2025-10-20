<div>
    {{-- Stop trying to control. --}}
    @if ($showLogout)
        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full inline-flex justify-center px-4 py-2 text-sm rounded-lg bg-emerald-600 hover:bg-emerald-700 
                       dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white shadow-sm transition-all duration-200 
                       focus-visible:ring-2 focus-visible:ring-emerald-500">
                    Kembali ke Halaman Login
                </button>
            </form>
        </div>
    @endif
</div>
