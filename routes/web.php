<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Livewire\Pages\PortalDashboard\Index;


Route::get('/', Index::class)->name('portal.index');

Route::post('/logout', function () {
    $keycloakBaseUrl = config('keycloak.base_url');
    $realm = config('keycloak.realm'); // misal: simashebat
    $redirectUri = url('/'); // arahkan kembali ke homepage Laravel setelah logout Keycloak

    // 2. URL logout keycloak
    $logoutUrl = "{$keycloakBaseUrl}/realms/{$realm}/protocol/openid-connect/logout?redirect_uri={$redirectUri}";

    // 3. Logout dari Laravel
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    // 4. Redirect ke logout Keycloak
    return redirect()->away($logoutUrl);
    
})->name('logout');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }

    // Jika belum login di Laravel, redirect ke login.silent (biar Keycloak yang tentukan)
    return redirect()->route('login.silent');
});

Route::get('/login/silent', function () {
    return Socialite::driver('keycloak')->redirect();
})->name('login.silent');

Route::get('/force-login', function () {
    return Socialite::driver('keycloak')
        ->with(['prompt' => 'login'])
        ->redirect();
})->name('force.login');


Route::get('/login/keycloak/callback', function () {

    try {
        $user = Socialite::driver('keycloak')->user();
        // dd($user); // Debugging: tampilkan informasi user yang didapat dari Keycloak

        // Buat login ke aplikasi Laravel, bisa pakai email / ID dari Keycloak
        $authUser = \App\Models\User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'name' => $user->getName(),
            'password' => bcrypt(Str::random(16)), // password random
        ]);

        Auth::login($authUser, true);

        Session::put('keycloak_id_token', $user->accessTokenResponseBody['id_token'] ?? null);

        return redirect('/');
    } catch (\Exception $e) {
        // Silent login gagal (karena user belum login di Keycloak)
        return redirect()->route('force.login'); // misalnya redirect ke login normal
    }
    
});
