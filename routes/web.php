<?php

use App\Constants\Constants;
use App\Livewire\Dashboard\Index;
use Illuminate\Support\Facades\Route;
use App\Livewire\SharedComponents\Profile;
use App\Livewire\Dashboard\Section\AppList;
use App\Livewire\Dashboard\Section\Support;
use App\Livewire\Dashboard\Section\Integration;
use App\Http\Controllers\Auth\KeycloakController;

// route untuk guest
Route::get('/', Index::class)->name('index');
Route::get('apps', AppList::class)->name('apps');
Route::get('supports', Support::class)->name('supports');
Route::get('integrations', Integration::class)->name('integrations');
Route::get('profiles', Profile::class)->name('profiles');

Route::get('/bantuan', function () {
    return redirect(Constants::HELP_URL);
})->name('help');

// Pengelolaan akun pindah ke Akun Kisara. Alamat lama tetap dialihkan karena
// sudah tersebar di bookmark, poster, dan pesan WhatsApp.
Route::get('reset-mfa', fn () => redirect()->away(account_url('/reset-mfa'), 301))->name('mfa.reset');
Route::get('update-whatsapp-number', fn () => redirect()->away(account_url('/personal-info/whatsapp'), 301));
Route::get('register-passkey', fn () => redirect()->away(account_url('/security/passkeys'), 301));

Route::get('/login', [KeycloakController::class, 'redirectToProvider'])->name('login');
Route::get('/login/keycloak/callback', [KeycloakController::class, 'handleProviderCallback'])->name('login.callback');
Route::post('/logout', [KeycloakController::class, 'logout'])->name('logout');
