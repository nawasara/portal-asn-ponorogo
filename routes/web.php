<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Livewire\Pages\PortalDashboard\Index;


Route::get('/', Index::class)->name('portal.index');

Route::post('/logout', function () {
     // Logout of your app.
    Auth::logout();
    Session::flush(); // Clear the session data
    Session::regenerate(); // Regenerate the session ID to prevent session fixation attacks
    
    // The URL the user is redirected to after logout.
    $redirectUri = Config::get('app.url');
    $url = Socialite::driver('keycloak')->getLogoutUrl($redirectUri, null, Session::put('keycloak_id_token')?? null);
    dd($url); // Debugging: tampilkan URL logout yang akan digunakan
    return redirect(Socialite::driver('keycloak')->getLogoutUrl($redirectUri, null, Session::put('keycloak_id_token')?? null));
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
