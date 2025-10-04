<?php
namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\SessionTrait;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class OtpForm extends Component
{
    use SessionTrait;

    public string $label = 'Masukkan OTP';
    public ?string $otp = null;
    public ?string $error = null;
    public int $otpTtlMinutes = 5;
    public bool $showResend = true;
    public bool $showForm = false;

    public $waNumber;
    public $userId = null;
    public $keycloakIdToken;
    public $keycloakUser;
    public $infoMessage;
    public $infoMessageType = 'success';

    // Event name to emit to parent
    public string $submitEvent = 'otpSubmitted';
    public string $resendEvent = 'otpResend';

    protected $rules = [
        'otp' => ['required', 'digits:6'],
    ];

    public function mount()
    {
        if (auth()->user()) {
            self::checkKeycloakSession(); // ada di trait
            $this->keycloakUser = self::getKeycloakUser(); // ada di trait
            $this->waNumber = $this->getNumber();
            $this->userId = Session::get('keycloak_id_user');
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

    #[On('send-otp')]
    public function sendOtp()
    {
        info('sendOtp called');
        info('No WhatsApp number for user ' . $this->userId);   
        info($this->waNumber);
        // simple throttle: max 3 sends per 10 minutes
        $attemptsKey = $this->getOtpAttemptsKey();
        $attempts = Cache::get($attemptsKey, 0);
        if ($attempts >= 300) {
            info('OTP send attempts exceeded for user ' . $this->userId);
            $this->addError('otp', 'Mencapai batas pengiriman OTP. Coba lagi nanti.');
            return;
        }

        // Generate OTP 6 digit
        $generatedOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke cache
        Cache::put($this->getOtpCacheKey(), $generatedOtp, now()->addMinutes($this->otpTtlMinutes));

        // increment attempts (expire 10 minutes)
        Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(10));

        $this->sendToWhatsapp($generatedOtp);

        self::setMessage("OTP telah dikirim ke +{$this->waNumber}.", 'success');
        $this->showForm = true;

        // emit event frontend supaya bisa autofocus ke OTP field
        $this->dispatch('otp-sent');
    }

    public function setMessage($message, $type = 'success')
    {
        $this->infoMessage = $message;
        $this->infoMessageType = $type;
    }

    public function resetMessage()
    {
        $this->infoMessage = null;
        $this->infoMessageType = null;
    }

    public function sendToWhatsapp($otp)
    {
        info($otp);return;
        if (!$this->waNumber) {
            self::setMessage('Nomor WhatsApp tidak ditemukan di profil Keycloak.', 'error');
            \info('No WhatsApp number for user ' . $this->userId);   
            return;
        }

        $waService = new \App\Services\WaNotificationService();
        try {
            $waService->sendWa($this->waNumber, "Kode OTP Anda: {$otp}. Berlaku {$this->otpTtlMinutes} menit.");
        } catch (\Exception $e) {
            self::setMessage('Gagal mengirim OTP via WhatsApp: ' . $e->getMessage(), 'error');
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
            info('OTP hasus diisi. Silahkan masukkan kode OTP');
            $this->addError('otp', 'OTP hasus diisi. Silahkan masukkan kode OTP.');
            return;
        }
        
        $this->validateOnly('otp');

        $cached = Cache::get($this->getOtpCacheKey());

        if (!$cached) {
            info('OTP sudah kadaluarsa. Silakan kirim ulang.');
            $this->addError('otp', 'OTP sudah kadaluarsa. Silakan kirim ulang.');
            return;
        }

        if (!hash_equals($cached, $this->otp)) {
            info('OTP tidak cocok. Input: ' . $this->otp . ', Cache: ' . $cached);
            $this->addError('otp', 'OTP tidak cocok.');
            return;
        }

        // OTP valid
        Cache::forget($this->getOtpCacheKey());
        Cache::forget($this->getOtpAttemptsKey());
        $this->resetMessage();
        $this->otp = null;
        $this->showForm = false;

        $this->dispatch('otp-valid');

    }

    public function render()
    {
        return view('livewire.components.otp-form');
    }
}
