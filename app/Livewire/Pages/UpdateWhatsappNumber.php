<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class UpdateWhatsappNumber extends Component
{
    public ?string $whatsapp_number = null;
    public bool $showOtpForm = false;
    public ?string $otp = null;
    public int $otpTtlMinutes = 5;
    public $userId;

    protected $rules = [
        'whatsapp_number' => ['required', 'regex:/^62[0-9]{8,13}$/'],
        'otp' => ['nullable', 'digits:6'],
    ];

    protected $messages = [
        'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
        'whatsapp_number.regex' => 'Format nomor harus diawali 62 dan hanya angka (contoh: 6281234567890).',
        'otp.digits' => 'OTP harus 6 digit.',
    ];

    public function mount()
    {
        // Ambil nomor yang sudah ada di DB (opsional) atau kosong
        

        // Ganti property ini sesuai ke mana kamu menyimpan keycloak id di model user
        $this->userId = $token = Session::get('keycloak_id_user');

        // Jika kamu menyimpan nomor WA di users table, bisa prefill:
        $this->whatsapp_number = $user->whatsapp_number ?? 6285736676648;
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
        $this->validateOnly('whatsapp_number');
        // dd($this->userId);
        // if (!$this->userId) {
            //     throw ValidationException::withMessages(['whatsapp_number' => 'Tidak dapat menentukan user id Keycloak.']);
            // }
            
            // simple throttle: max 3 sends per 10 minutes
        $attemptsKey = $this->getOtpAttemptsKey();
        $attempts = Cache::get($attemptsKey, 0);
        if ($attempts >= 3) {
            $this->addError('whatsapp_number', 'Mencapai batas pengiriman OTP. Coba lagi nanti.');
            // return;
        }

        // Update nomor di Keycloak (merge dilakukan di service)
        // try {
        //     $keycloak->updateWhatsappNumber($this->userId, $this->whatsapp_number);
        // } catch (\Exception $e) {
        //     $this->addError('whatsapp_number', 'Gagal menyimpan nomor ke Keycloak: ' . $e->getMessage());
        //     return;
        // }

        // Generate OTP 6 digit
        $generatedOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke cache
        Cache::put($this->getOtpCacheKey(), $generatedOtp, now()->addMinutes($this->otpTtlMinutes));

        // increment attempts (expire 10 minutes)
        Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(10));

        // Di sinilah kamu panggil service pengirim WA (misal job / service eksternal) untuk kirim OTP:
        // dispatch(new SendWhatsappOtpJob($this->whatsapp_number, $generatedOtp));
        // Untuk sekarang kita mock / contoh: log atau session flash

        $this->sendToWhatsapp($generatedOtp);

        session()->flash('success', "OTP telah dikirim ke +{$this->whatsapp_number} (mock).");

        $this->showOtpForm = true;

        // emit event frontend supaya bisa autofocus ke OTP field
        $this->dispatch('otp-sent');
    }

    public function sendToWhatsapp($otp)
    {
        $waService = new \App\Services\WaNotificationService();
        try {
            $waService->sendWa($this->whatsapp_number, "Kode OTP Anda: {$otp}. Berlaku {$this->otpTtlMinutes} menit.");
        } catch (\Exception $e) {
            $this->addError('whatsapp_number', 'Gagal mengirim OTP via WhatsApp: ' . $e->getMessage());
            dd($e->getMessage());
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

        // Tandai verifikasi di session / database
        session(['whatsapp_verified' => true]);

        // Jika kamu ingin menyimpan flag verified di DB:
        // $user = auth()->user();
        // $user->whatsapp_verified_at = now();
        // $user->save();

        $service = new KeycloakService();
        $service->updateWhatsappNumber($this->userId, $this->whatsapp_number);

        info('Nomor WhatsApp user ID '.$this->userId.' terverifikasi.' . ' Nomor: '.$this->whatsapp_number);

        session()->flash('success', 'Nomor WhatsApp berhasil diverifikasi.');

        // Redirect ke intended atau dashboard
        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.pages.update-whatsapp-number');
    }
}
