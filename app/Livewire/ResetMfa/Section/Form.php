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
    public ?string $email = null;
    public bool $emailEmpty = false; // true → tampilkan opsi fallback WhatsApp
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
        $this->email = $this->keycloakUser['email'] ?? null;
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
        $this->email = $this->keycloakUser['email'] ?? null;
        $this->userId = $this->keycloakUser['id'];

        return true;
    }

    /**
     * Channel default: EMAIL. Bila email user kosong, tampilkan opsi fallback WhatsApp.
     */
    public function sendOtp()
    {
        if (!self::resolveAndVerifyUser()) return;

        // Jalur utama: email.
        if ($this->email) {
            $this->emailEmpty = false;
            $this->showForm = false;
            $this->dispatch('send-otp', email: $this->email, channel: 'email', identity: $this->nip);
            return;
        }

        // Email kosong → tawarkan fallback WhatsApp (tombol muncul di view).
        $this->emailEmpty = true;
        $this->addError('nip', 'Email Anda belum terdaftar. Kirim OTP via WhatsApp, atau hubungi bantuan.');
    }

    /**
     * Fallback: kirim OTP via WhatsApp (dipakai saat email kosong).
     */
    public function sendOtpViaWa()
    {
        if (!self::resolveAndVerifyUser()) return;

        if (!$this->waNumber) {
            $this->addError('nip', 'Nomor WhatsApp Anda juga belum terdaftar. Silakan klik link bantuan.');
            return;
        }

        if (!self::waNumberIsValid()) return;

        $this->showForm = false;
        $this->dispatch('send-otp', waNumber: $this->waNumber, channel: 'wa', identity: $this->nip);
    }

    /**
     * Validasi NIP + resolve user Keycloak + cek username cocok.
     * Return true bila lolos. Mengisi $this->email / $this->waNumber.
     */
    protected function resolveAndVerifyUser(): bool
    {
        $this->validateOnly('nip');

        if (!$this->isAuth) {
            if (!self::getKeycloakProfile()) return false;
        }

        if ($this->nip != ($this->keycloakUser['username'] ?? null)) {
            $this->addError('nip', 'NIP Anda salah.');
            return false;
        }

        return true;
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
        $this->showForm = true; // tampilkan lagi form NIP supaya user bisa coba lagi
        $this->addError('nip', 'Pengiriman OTP dibatasi 3 kali dalam 10 menit. Silakan coba lagi nanti.');
    }

    /**
     * Pengiriman OTP gagal (mis. SMTP error) — tampilkan lagi form NIP agar user
     * tidak buntu. Pesan error detail sudah ditampilkan oleh OtpForm.
     */
    #[On('otp-send-failed')]
    public function otpSendFailed()
    {
        $this->showForm = true;
    }

    public function render()
    {
        return view('livewire.reset-mfa.section.form');
    }
}
