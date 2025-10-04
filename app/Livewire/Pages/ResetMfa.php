<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\SessionTrait;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class ResetMfa extends Component
{
    use SessionTrait;
    
    public ?string $nip = null;
    public bool $showOtpForm = false;
    public bool $showBtnLoginForm = false;
    public $userId;
    public $keycloakIdToken;
    public $keycloakUser;

    protected $rules = [
        'nip' => ['required', 'digits:18'],
    ];

    protected $messages = [
        'nip.required' => 'NIP wajib diisi.',
        'nip.digits' => 'NIP harus 18 digit.',
    ];

    public function mount()
    {
        // Ganti property ini sesuai ke mana kamu menyimpan keycloak id di model user
        $this->userId = Session::get('keycloak_id_user');
        $this->keycloakIdToken = Session::get('keycloak_id_token');

        // Jika kamu menyimpan nomor WA di users table, bisa prefill:
        $this->nip = 199506142020121004;

        self::checkKeycloakSession(); // ada di trait
        $this->keycloakUser = self::getKeycloakUser(); // ada di trait
    }

    public function sendOtp()
    {
        // Validasi nomor
        $this->validateOnly('nip');

        if ($this->nip != $this->keycloakUser['username']) {
            $this->addError('nip', 'NIP Anda salah.');
            return;
        }

        $this->showOtpForm = true;

        // emit event frontend supaya bisa autofocus ke OTP field
        $this->dispatch('send-otp');
    }

    #[On('otp-valid')]
    public function verifyOtp()
    {
        // $service = new KeycloakService();
        // $status = $service->resetOtp($this->userId);
    
        session()->flash('success', 'Berhasil mereset MFA. Silakan masuk kembali menggunakan NIP dan scan ulang QRCode MFA Anda.');

        $this->showBtnLoginForm = true;

        return;
    }
    
    public function render()
    {
        return view('livewire.pages.reset-mfa');
    }
}
