<?php

use Illuminate\Support\Str;
use App\Constants\Constants;
use App\Livewire\Pages\Guest;
use App\Livewire\Dashboard\Index;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Livewire\SharedComponents\Profile;
use App\Livewire\Dashboard\Section\AppList;
use App\Livewire\Dashboard\Section\Support;
use App\Livewire\ResetMfa\Index as ResetMfa;
use App\Livewire\Dashboard\Section\Integration;
use App\Livewire\Pages\ResetMfaUnauthorization;
use App\Http\Controllers\Auth\KeycloakController;
use App\Livewire\UpdateWhatsappNumber\Index as UpdateWhatsappNumber;

// route untuk guest
Route::get('/', Index::class)->middleware(['whatsapp.required.if.auth'])->name('index');
Route::get('apps', AppList::class)->name('apps');
Route::get('supports', Support::class)->name('supports');
Route::get('integrations', Integration::class)->name('integrations');
Route::get('profiles', Profile::class)->name('profiles');
Route::get('/update-whatsapp-number', UpdateWhatsappNumber::class)
    ->middleware(['auth']) // pastikan hanya user terautentikasi
    ->name('update-whatsapp-number');
    
Route::get('reset-mfa-unauthorization', ResetMfaUnauthorization::class)->name('mfa.reset-unauthorization');
Route::get('/bantuan', function () {
    return redirect(Constants::HELP_URL);
})->name('help');

Route::get('reset-mfa', ResetMfa::class)->middleware(['auth', 'whatsapp.required'])->name('mfa.reset');

Route::get('/login', [KeycloakController::class, 'redirectToProvider'])->name('login');
Route::get('/login/keycloak/callback', [KeycloakController::class, 'handleProviderCallback'])->name('login.callback');
Route::post('/logout', [KeycloakController::class, 'logout'])->name('logout');


