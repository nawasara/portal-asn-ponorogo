<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class ResetMfa extends Component
{
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
        $this->nip = 199506142020121004;

        self::checkKeycloakSession();
        self::getKeycloakUser();

        /* Ceck cache OTP */
        $cached = Cache::get($this->getOtpCacheKey());
        if ($cached) {
            $this->showOtpForm = true;
        }
    }

    public function getKeycloakUser()
    {
        try {
            $service = new KeycloakService();
            $this->keycloakUser = $service->getUser($this->userId);
        } catch (\Exception $e) {
            info('Gagal mengambil info user dari Keycloak: ' . $e->getMessage());
            self::logout();
        }
    }

    public function checkKeycloakSession()
    {
        $token = Session::get('keycloak_id_token');
        if (!$token) { self::logout();}

        try {
            $service = new KeycloakService();
            $res = $service->checkLoginStatus($token);
            if (!$res['active']) {
                self::logout();
            }
        } catch (\Exception $e) {
            self::logout();
        }
    }

    public function logout($redirect = true)
    {
        Auth::logout();
        Session::flush(); // Clear the session data
        Session::regenerate(); // Regenerate the session ID to prevent session fixation attacks  

        if ($this->keycloakIdToken) {
            // The URL the user is redirected to after logout.
            $redirectUri = Config::get('app.url');
            $url = Socialite::driver('keycloak')->getLogoutUrl();
            $params = [
                'id_token_hint' => $this->keycloakIdToken, // Ambil id_token dari session
                'post_logout_redirect_uri' => $redirectUri, // URL redirect setelah logout
            ];
    
            $url .= '?' . http_build_query($params);
    
            return redirect($url);
        }
    }

    protected function getOtpCacheKey(): string
    {
        return 'wa:mfa-reset.otp:' . $this->userId;
    }

    protected function getOtpAttemptsKey(): string
    {
        return 'wa:mfa-reset.otp:attempts:' . $this->userId;
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

    public function getNumber()
    {
        $token = Session::get('keycloak_id_user');

        $service = new \App\Services\KeycloakService();
        return $service->getWhatsappNumber($token);
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
