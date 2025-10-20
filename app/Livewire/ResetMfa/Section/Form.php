<?php

namespace App\Livewire\ResetMfa\Section;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\SessionTrait;
use App\Services\KeycloakService;
use App\Services\WaNotificationService;
use Illuminate\Support\Facades\Session;

class Form extends Component
{
    use SessionTrait;
    
    public ?string $nip = null;
    public $userId;
    public $keycloakUser;
    public bool $showForm = true;
    public $waNumber;
    public bool $isAuth = false;

    protected $rules = [
        'nip' => ['required', 'digits:18'],
    ];

    protected $messages = [
        'nip.required' => 'NIP wajib diisi.',
        'nip.digits' => 'NIP harus 18 digit.',
    ];

    public function mount()
    {
        $this->isAuth = auth()->check();
        $this->initAuthData();
    }

    public function initAuthData()
    {
        if (!$this->isAuth) return;

        $this->userId = Session::get('keycloak_id_user');
        // $this->nip = 199506142020121004;
        self::checkKeycloakSession(); // ada di trait
        $this->keycloakUser = self::getKeycloakUser(); // ada di trait
        $this->waNumber = $this->keycloakUser['attributes']['whatsapp_number'][0] ?? null;
    }

    public function getKeycloakProfile()
    {
        $service = new KeycloakService();
        $this->keycloakUser = $service->getUserByUsername($this->nip);
        if (!$this->keycloakUser) {
            $this->addError('nip', 'NIP Anda tidak terdaftar.');
            return false;
        }

        $this->waNumber = $this->keycloakUser['attributes']['whatsapp_number'][0] ?? null;
        $this->userId = $this->keycloakUser['id'];

        if (!$this->waNumber) {
            $this->addError('nip', 'Nomor WhatsApp Anda belum terdaftar. Silakan klik link bantuan.');
            return false;
        }

        return true;
    }

    public function sendOtp()
    {
        // Validasi nomor
        $this->validateOnly('nip');

        if (!$this->isAuth) {
            $isTrue = self::getKeycloakProfile();
            if (!$isTrue) return;
        }

        if (!self::waNumberIsValid()) return;

        if ($this->nip != $this->keycloakUser['username']) {
            $this->addError('nip', 'NIP Anda salah.');
            return;
        }

        $this->showForm = false;
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

        /* tampilkan logout button */
        $this->dispatch('show-logout');

        return;
    }

    #[On('otp-reach-limit')]
    public function otpReachLimit()
    {
        $this->addError('nip', 'Pengiriman OTP ke nomor WhatsApp Anda dibatasi 3 kali dalam 10 menit. Silakan coba lagi nanti.');
    }

    public function render()
    {
        return view('livewire.reset-mfa.section.form');
    }
}
