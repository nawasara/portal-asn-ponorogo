<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\SessionTrait;

class ResetMfa extends Component
{
    use SessionTrait;
    
    public ?string $nip = null;
    public bool $showOtpForm = false;
    public bool $showBtnLoginForm = false;
    public ?string $otp = null;
    public int $otpTtlMinutes = 5;
    public $userId;
    public $keycloakIdToken;
    public $keycloakUser;

    protected $rules = [
        'nip' => ['required', 'digits:18'],
        'otp' => ['nullable', 'digits:6'],
    ];

    protected $messages = [
        'nip.required' => 'NIP wajib diisi.',
        'nip.digits' => 'NIP harus 18 digit.',
        'otp.digits' => 'OTP harus 6 digit.',
    ];

    public function mount()
    {
        // Ganti property ini sesuai ke mana kamu menyimpan keycloak id di model user
        $this->userId = Session::get('keycloak_id_user');
        $this->keycloakIdToken = Session::get('keycloak_id_token');

        // Jika kamu menyimpan nomor WA di users table, bisa prefill:
        $this->nip = null;

        self::checkKeycloakSession(); // ada di trait
        $this->keycloakUser = self::getKeycloakUser(); // ada di trait

        /* Ceck cache OTP */
        $cached = Cache::get($this->getOtpCacheKey());
        if ($cached) {
            $this->showOtpForm = true;
        }
    }

    protected function getOtpCacheKey(): string
    {
        return 'wa:otp:' . $this->userId;
    }

    protected function getOtpAttemptsKey(): string
    {
        return 'wa:otp:attempts:' . $this->userId;
    }

    public function sendOtp(KeycloakService $keycloak)
    {
        // Validasi nomor
        $this->validateOnly('nip');

        if ($this->nip != $this->keycloakUser['username']) {
            $this->addError('nip', 'NIP Anda salah.');
            return;
        }
        // simple throttle: max 3 sends per 10 minutes
        $attemptsKey = $this->getOtpAttemptsKey();
        $attempts = Cache::get($attemptsKey, 0);
        if ($attempts >= 3) {
            $this->addError('nip', 'Mencapai batas pengiriman OTP. Coba lagi nanti.');
            return;
        }

        // Generate OTP 6 digit
        $generatedOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke cache
        Cache::put($this->getOtpCacheKey(), $generatedOtp, now()->addMinutes($this->otpTtlMinutes));

        // increment attempts (expire 10 minutes)
        Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(10));

        $this->sendToWhatsapp($generatedOtp);

        session()->flash('success', "OTP telah dikirim ke +{$this->getNumber()}.");

        $this->showOtpForm = true;

        // emit event frontend supaya bisa autofocus ke OTP field
        $this->dispatch('otp-sent');
    }

    public function sendToWhatsapp($otp)
    {
        $number = $this->getNumber();
        if (!$number) {
            $this->addError('nip', 'Nomor WhatsApp tidak ditemukan di profil Keycloak.');
            return;
        }

        $waService = new \App\Services\WaNotificationService();
        try {
            $waService->sendWa($number, "Kode OTP Anda: {$otp}. Berlaku {$this->otpTtlMinutes} menit.");
        } catch (\Exception $e) {
            $this->addError('nip', 'Gagal mengirim OTP via WhatsApp: ' . $e->getMessage());
            return;
        }
    }

    public function resendOtp(KeycloakService $keycloak)
    {
        // optional: kita reuse sendOtp but do not revalidate heavy
        $this->otp = null;
        $this->sendOtp($keycloak);
    }

    public function verifyOtp()
    {
        if (!$this->otp) {
            $this->addError('otp', 'OTP hasus diisi. Silahkan masukkan kode OTP.');
            return;
        }
        
        $this->validateOnly('otp');

        $cached = Cache::get($this->getOtpCacheKey());

        if (!$cached) {
            $this->addError('otp', 'OTP sudah kadaluarsa. Silakan kirim ulang.');
            return;
        }

        if (!hash_equals($cached, $this->otp)) {
            $this->addError('otp', 'OTP tidak cocok.');
            return;
        }

        // OTP valid
        Cache::forget($this->getOtpCacheKey());
        Cache::forget($this->getOtpAttemptsKey());

        $service = new KeycloakService();
        $status = $service->resetOtp($this->userId);
    
        session()->flash('success', 'Berhasil mereset MFA. Silakan masuk kembali menggunakan NIP dan scan ulang QRCode MFA Anda.');

        $this->showBtnLoginForm = true;

        return;

    }
    
    public function render()
    {
        return view('livewire.pages.reset-mfa');
    }
}
