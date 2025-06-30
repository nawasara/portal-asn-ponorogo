<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return Socialite::driver('keycloak')->redirect();
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

    return redirect('/');
});
