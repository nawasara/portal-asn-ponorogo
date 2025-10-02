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
use App\Http\Controllers\Auth\KeycloakController;

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



Route::post('/logout', [KeycloakController::class, 'logout'])->name('logout');
Route::get('/login', [KeycloakController::class, 'redirectToProvider'])->name('login');
Route::get('/login/keycloak/callback', [KeycloakController::class, 'handleProviderCallback'])->name('login.callback');
