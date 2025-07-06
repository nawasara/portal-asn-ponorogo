<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Livewire\Pages\PortalDashboard\Index;


Route::get('/', Index::class)->name('portal.index');

Route::post('/logout', function () {
    $idToken = Session::get('keycloak_id_token'); // bisa null
    Auth::logout();
    Session::invalidate();

    $redirectUri = route('portal.index'); // ganti ke dashboard atau homepage

    $keycloakLogout = 'https://login.ponorogo.go.id/realms/simashebat/protocol/openid-connect/logout?' . http_build_query([
        'post_logout_redirect_uri' => $redirectUri,
        'id_token_hint' => $idToken, // opsional tapi direkomendasikan
    ]);

    return redirect('/');
    
})->name('logout');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }

    // Coba silent login dulu via prompt=none
    return Socialite::driver('keycloak')
        ->with(['prompt' => 'none'])
        ->redirect();
});

Route::get('/login/keycloak/callback', function () {
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
});
