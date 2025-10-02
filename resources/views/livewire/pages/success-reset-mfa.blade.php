<div class="min-h-screen flex items-center justify-center bg-gray-50 p-6 dark:bg-gray-900">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h2 class="text-xl font-semibold mb-4">Reset MFA</h2>

            <div class="mb-4 text-sm bg-green-50 border border-green-200 text-green-700 p-3 rounded">
                Berhasil mereset MFA. Silakan masuk kembali menggunakan NIP dan scan ulang QRCode MFA Anda.
            </div>
            <div class="mt-6 border-t pt-4">
                <a href="{{ url('/login') }}"
                    class="w-full inline-flex justify-center px-4 py-2 bg-blue-400 rounded-md text-sm font-medium border hover:bg-gray-50">
                    Kembali ke Halaman Login
                </a>
            </div>
        </div>
    </div>
</div>
