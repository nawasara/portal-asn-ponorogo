<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\SessionTrait;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Services\WaNotificationService;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class ResetMfaUnauthorization extends Component
{
    use SessionTrait;
    
    public ?string $nip = null;
    public bool $showBtnLoginForm = false;
    public $userId;
    public $keycloakUser;
    public bool $showOtpForm = false;
    public $waNumber;

    protected $rules = [
        'nip' => ['required', 'digits:18'],
    ];

    protected $messages = [
        'nip.required' => 'NIP wajib diisi.',
        'nip.digits' => 'NIP harus 18 digit.',
    ];

    public function mount()
    {
    }

    public function sendOtp()
    {
        // Validasi nomor
        $this->validateOnly('nip');

        $service = new KeycloakService();
        $this->keycloakUser = $service->getUserByUsername($this->nip);
        if (!$this->keycloakUser) {
            $this->addError('nip', 'NIP Anda tidak terdaftar.');
            return;
        }

        $this->waNumber = $this->keycloakUser['attributes']['whatsapp_number'][0] ?? null;
        $this->userId = $this->keycloakUser['id'];

        if (!$this->waNumber) {
            $this->addError('nip', 'Nomor WhatsApp Anda belum terdaftar. Silakan klik link bantuan.');
            return;
        }


        if (!self::waNumberIsValid()) return;

        $this->showOtpForm = true;

        // emit event frontend supaya bisa autofocus ke OTP field
        $this->dispatch('send-otp', waNumber: $this->waNumber);
    }

    public function waNumberIsValid()
    {
        $waService = new WaNotificationService();
        try {
            $r = $waService->checkNumber($this->waNumber);
            if (!$r) {
                $this->addError('nip', 'Nomor yang Anda masukkan tidak terdaftar di WhatsApp. Silakan gunakan nomor lain.');
                return false;
            }

            return true;
        } catch (\Exception $e) {
            $this->addError('nip', 'Gagal mengirim OTP via WhatsApp: ' . $e->getMessage());
            
            return false;
        }
    }

    #[On('otp-valid')]
    public function verifyOtp()
    {
        $service = new KeycloakService();
        $status = $service->resetOtp($this->userId);
    
        session()->flash('success', 'Berhasil mereset MFA. Silakan masuk kembali menggunakan NIP dan scan ulang QRCode MFA Anda.');

        $this->showBtnLoginForm = true;

        return;
    }

    #[On('otp-reach-limit')]
    public function otpReachLimit()
    {
        $this->addError('nip', 'Pengiriman OTP ke nomor WhatsApp Anda dibatasi 3 kali dalam 10 menit. Silakan coba lagi nanti.');
        $this->showOtpForm = false;
    }

    public function render()
    {
        return view('livewire.pages.reset-mfa-unauthorization');
    }
}
