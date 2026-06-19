<?php

namespace App\Livewire\UpdateWhatsappNumber\Section;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Traits\SessionTrait;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\WaNotificationService;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class Form extends Component
{
    public ?string $whatsapp_number = null;
    public $userId;
    public bool $showOtpForm = false;

    protected $listeners = [
        'request-send-otp' => 'handleRequestSendOtp',
    ];

    protected $rules = [
        'whatsapp_number' => ['required', 'regex:/^08[0-9]{8,12}$/'],
    ];

    /**
     * Strip masking (strip/spasi) dan normalisasi prefix ke 08xxx.
     * Input bisa datang sebagai "0857-3667-6648", "+62857...", "62857...".
     */
    protected function normalizeNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        // Buang semua karakter selain digit (strip, spasi, +, dst).
        $digits = preg_replace('/\D+/', '', $number);

        // Samakan prefix 62xxx / +62xxx menjadi 0xxx untuk divalidasi seragam.
        if (Str::startsWith($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }

    protected $messages = [
        'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
        'whatsapp_number.regex' => 'Format nomor harus diawali 08 dan hanya angka (contoh: 081234567890).',
    ];

    public function mount()
    {
        // Ganti property ini sesuai ke mana kamu menyimpan keycloak id di model user
        $this->userId = Session::get('keycloak_id_user');

        // Jika kamu menyimpan nomor WA di users table, bisa prefill:
        $this->whatsapp_number = Auth::user()?->whatsapp_number ?? null;
    }

    protected function getWACacheKey(): string
    {
        return 'wa:number:' . $this->userId;
    }

    public function waNumberIsValid()
    {
        $waService = new WaNotificationService();
        try {
            $r = $waService->checkNumber($this->whatsapp_number);
            if (!$r) {
                $this->addError('whatsapp_number', 'Nomor yang Anda masukkan tidak terdaftar di WhatsApp. Silakan gunakan nomor lain.');
                return false;
            }

            return true;
        } catch (\Exception $e) {
            // Layanan WA bermasalah (sesi terputus / 401 / timeout) — BUKAN nomor invalid.
            $this->addError('whatsapp_number', $e->getMessage().' Silakan coba beberapa saat lagi.');

            return false;
        }
    }

    public function sendOtp($resend = false)
    {
        $this->whatsapp_number = $this->whatsapp_number ? : Cache::get($this->getWACacheKey());

        // Buang masking (strip/spasi) + samakan prefix ke 08xxx sebelum divalidasi.
        $this->whatsapp_number = $this->normalizeNumber($this->whatsapp_number);

        // Validasi nomor
        $this->validateOnly('whatsapp_number');

        self::formatNumber();

        // WAGO sedang tidak tersedia (mis. nomor kena ban) → lewati cek nomor +
        // pengiriman OTP. Nomor langsung disimpan tanpa verifikasi OTP.
        if (! config('services.whatsapp.verification_enabled', true)) {
            $this->verifyOtp();
            return;
        }

        if (!self::waNumberIsValid()) return;

        $this->showOtpForm = true;
        $this->dispatch('send-otp', waNumber: $this->whatsapp_number);
    }

    public function formatNumber()
    {
        if (! $this->whatsapp_number) {
            return;
        }

        // Sudah berformat 62xxx (mis. resend) — jangan double-prefix.
        if (Str::startsWith($this->whatsapp_number, '62')) {
            return;
        }

        $this->whatsapp_number = '62' . substr($this->whatsapp_number, 1);
    }

    #[On('otp-valid')]
    public function verifyOtp()
    {
        $verified = config('services.whatsapp.verification_enabled', true);

        $service = new KeycloakService();
        $service->updateWhatsappNumber($this->userId, $this->whatsapp_number);

        info('Nomor WhatsApp user ID '.$this->userId.' '.($verified ? 'terverifikasi' : 'disimpan (verifikasi dilewati)').'. Nomor: '.$this->whatsapp_number);

        session()->flash('success', $verified
            ? 'Nomor WhatsApp berhasil diverifikasi.'
            : 'Nomor WhatsApp berhasil disimpan.');

        $this->redirect('/');

    }

    
    #[On('otp-reach-limit')]
    public function otpReachLimit()
    {
        $this->addError('whatsapp_number', 'Pengiriman OTP ke nomor WhatsApp Anda dibatasi 3 kali dalam 10 menit. Silakan coba lagi nanti.');
        $this->showOtpForm = false;
    }
    
    public function render()
    {
        return view('livewire.update-whatsapp-number.section.form');
    }
}
