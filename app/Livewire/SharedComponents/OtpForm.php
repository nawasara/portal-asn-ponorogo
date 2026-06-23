<?php
namespace App\Livewire\SharedComponents;

use App\Mail\OtpMail;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Traits\SessionTrait;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Services\WaNotificationService;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class OtpForm extends Component
{
    use SessionTrait;

    public string $label = 'Masukkan OTP';
    public ?string $otp = null;
    public ?string $error = null;
    public int $otpTtlMinutes = 60; // OTP berlaku 1 jam (email bisa delay; berlaku utk semua channel)
    public bool $showResend = true;
    public bool $showForm = false;
    public bool $showAlert = false;

    public $waNumber;
    public ?string $email = null;
    public string $channel = 'wa'; // 'wa' | 'email' — default wa agar flow lama tak berubah
    public $userId = null;
    public ?string $identity = null; // NIP/userId target — dikirim parent agar throttle benar walau anonim
    public $keycloakIdToken;
    public $keycloakUser;
    public $infoMessage;
    public $infoMessageType = 'success';

    protected $rules = [
        'otp' => ['required', 'digits:6'],
    ];

    protected $messages = [
        'otp.required' => 'OTP wajib diisi.',
        'otp.digits' => 'OTP harus 6 digit.',
    ];

    public function mount()
    {
        if (auth()->user()) {
            self::checkKeycloakSession(); // ada di trait
            $this->keycloakUser = self::getKeycloakUser(); // ada di trait
            $this->userId = Session::get('keycloak_id_user');
        }
    }

    /**
     * Identitas target untuk kunci cache: NIP/userId yang dikirim parent, atau
     * userId sesi bila login. Fallback ke 'anon' (sangat jarang) supaya tidak
     * pernah jadi string kosong yang membuat counter global antar-user.
     */
    protected function otpIdentity(): string
    {
        return (string) ($this->identity ?: $this->userId ?: 'anon');
    }

    protected function getOtpCacheKey(): string
    {
        return 'otp:code:' . $this->otpIdentity();
    }

    /**
     * Throttle pengiriman per NIP + IP. Dengan NIP, dua user berbeda di IP yang
     * sama (mis. satu kantor) tetap punya counter terpisah; IP menambah proteksi
     * agar satu klien tidak spam lintas NIP.
     */
    protected function getOtpAttemptsKey(): string
    {
        return 'otp:attempts:' . $this->otpIdentity() . ':' . request()->ip();
    }

    #[On('send-otp')]
    public function sendOtp($waNumber = null, $email = null, $channel = null, $identity = null)
    {
        if ($channel) {
            $this->channel = $channel;
        }
        if ($waNumber) {
            $this->waNumber = $waNumber;
        }
        if ($email) {
            $this->email = $email;
        }
        if ($identity) {
            $this->identity = $identity;
        }

        // Pastikan target sesuai channel terisi.
        if ($this->channel === 'email') {
            if (!$this->email) {
                $this->addError('otp', 'Alamat email kosong.');
                return;
            }
        } else {
            if (!$this->waNumber) {
                $this->addError('otp', 'Nomor WhatsApp kosong.');
                return;
            }
        }

        // simple throttle: max 3 sends per 10 minutes
        $attemptsKey = $this->getOtpAttemptsKey();
        $attempts = Cache::get($attemptsKey, 0);
        if ($attempts >= 3) {
            info('OTP send attempts exceeded for user ' . $this->userId);
            $this->addError('otp', 'Mencapai batas pengiriman OTP. Coba lagi nanti.');
            $this->dispatch('otp-reach-limit');
            return;
        }

        // Generate OTP 6 digit
        $generatedOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke cache
        Cache::put($this->getOtpCacheKey(), $generatedOtp, now()->addMinutes($this->otpTtlMinutes));

        // increment attempts (expire 10 minutes)
        Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(10));

        if ($this->channel === 'email') {
            $sent = $this->sendToEmail($generatedOtp);
            if ($sent === false) {
                // Kirim gagal — beri tahu parent agar form NIP ditampilkan lagi (tidak buntu).
                $this->dispatch('otp-send-failed');
                return; // pesan error sudah di-set di sendToEmail
            }
            self::setMessage("OTP telah dikirim ke email " . mask_email($this->email), 'success');
        } else {
            $this->sendToWhatsapp($generatedOtp);
            self::setMessage("OTP telah dikirim ke nomor WhatsApp " . mask_phone($this->waNumber), 'success');
        }

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
        if (!$this->waNumber) {
            self::setMessage('Nomor WhatsApp tidak ditemukan.', 'error');
            \info('No WhatsApp number for user ' . $this->userId);   
            return;
        }

        $uuid = Str::uuid()->toString();
        $message = "Kode OTP Anda: {$otp}. Berlaku {$this->otpTtlMinutes} menit. (Ref: {$uuid})";

        $waService = new WaNotificationService();
        try {
            $waService->sendWa($this->waNumber, $message);
        } catch (\Exception $e) {
            self::setMessage('Gagal mengirim OTP via WhatsApp: ' . $e->getMessage(), 'error');
            return;
        }
    }

    /**
     * Kirim OTP via email (Laravel Mailer). Return false bila gagal kirim
     * (pesan error sudah di-set), true bila sukses.
     */
    public function sendToEmail($otp): bool
    {
        if (!$this->email) {
            self::setMessage('Alamat email tidak ditemukan.', 'error');
            \info('No email for user ' . $this->userId);
            return false;
        }

        $ref = Str::uuid()->toString();

        try {
            Mail::to($this->email)->send(new OtpMail($otp, $this->otpTtlMinutes, $ref));
        } catch (\Throwable $e) {
            self::setMessage('Gagal mengirim OTP via Email: ' . $e->getMessage(), 'error');
            \info('Gagal kirim OTP email user ' . $this->userId . ': ' . $e->getMessage());
            return false;
        }

        return true;
    }

    public function resendOtp()
    {
        // optional: kita reuse sendOtp but do not revalidate heavy
        $this->otp = null;
        $this->sendOtp();
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
        return view('livewire.shared-components.otp-form');
    }
}
