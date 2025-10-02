<?php

use Illuminate\Support\Str;
use App\Constants\Constants;
use App\Livewire\Pages\Guest;
use App\Livewire\Pages\ResetMfa;
use App\Livewire\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Livewire\Pages\UpdateWhatsappNumber;

// route untuk guest
Route::get('/', Guest::class)->name('index');
Route::get('/update-whatsapp-number', UpdateWhatsappNumber::class)
    ->middleware(['auth']) // pastikan hanya user terautentikasi
    ->name('update-whatsapp-number');
    
Route::get('/bantuan', function () {
    return redirect(Constants::HELP_URL);
})->name('help');

// route untuk authenticated
Route::middleware(['auth', 'whatsapp.required'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('reset-mfa', ResetMfa::class)->name('mfa.reset');
});

Route::post('/logout', function () {
    $token = Session::get('keycloak_id_token');
    Auth::logout();
    Session::flush(); // Clear the session data
    Session::regenerate(); // Regenerate the session ID to prevent session fixation attacks
    
    // The URL the user is redirected to after logout.
    $redirectUri = Config::get('app.url');
    $url = Socialite::driver('keycloak')->getLogoutUrl();
    $params = [
        'id_token_hint' => $token, // Ambil id_token dari session
        'post_logout_redirect_uri' => $redirectUri, // URL redirect setelah logout
    ];

    $url .= '?' . http_build_query($params);

    return redirect($url);
})->name('logout');

Route::get('/login', function () {
    return Socialite::driver('keycloak')->redirect();
})->name('login');

Route::get('/login/keycloak/callback', function () {

    try {
        $user = Socialite::driver('keycloak')->user();

        // Buat login ke aplikasi Laravel, bisa pakai email / ID dari Keycloak
        $authUser = \App\Models\User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'name' => $user->getName(),
            'password' => bcrypt(Str::random(16)),
        ]);

        Auth::login($authUser, true);

        Session::put('keycloak_id_token', $user->accessTokenResponseBody['id_token']);
        Session::put('keycloak_id_user', $user->id);

        return redirect('/');
    } catch (\Exception $e) {
        return redirect()->route('index');
    }
})->name('login.callback');
