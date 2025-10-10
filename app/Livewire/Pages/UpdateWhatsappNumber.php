<?php

namespace App\Livewire\Pages;

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


class UpdateWhatsappNumber extends Component
{
    use SessionTrait;
    public ?string $whatsapp_number = null;
    public $userId;
    public bool $showOtpForm = false;

    protected $rules = [
        'whatsapp_number' => ['required', 'regex:/^08[0-9]{8,12}$/'],
    ];

    protected $messages = [
        'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
        'whatsapp_number.regex' => 'Format nomor harus diawali 08 dan hanya angka (contoh: 081234567890).',
    ];

    public function mount()
    {
        // Ganti property ini sesuai ke mana kamu menyimpan keycloak id di model user
        $this->userId = $token = Session::get('keycloak_id_user');
        
        // Jika kamu menyimpan nomor WA di users table, bisa prefill:
        $this->whatsapp_number = $user->whatsapp_number ?? null;
        
        self::checkKeycloakSession();
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
            $this->addError('whatsapp_number', 'Gagal mengirim OTP via WhatsApp: ' . $e->getMessage());
            
            return false;
        }
    }

    public function sendOtp($resend = false)
    {
        $this->whatsapp_number = $this->whatsapp_number ? : Cache::get($this->getWACacheKey());

        // Validasi nomor
        $this->validateOnly('whatsapp_number');

        self::formatNumber();
        // if (!self::waNumberIsValid()) return;

        // $this->showOtpForm = true;
        // $this->dispatch('send-otp', waNumber: $this->whatsapp_number);

        self::verifyOtp();
    }

    public function formatNumber()
    {
        if ($this->whatsapp_number) {
            $this->whatsapp_number = '62' . substr($this->whatsapp_number, 1);
        }
    }

    #[On('otp-valid')]
    public function verifyOtp()
    {
        $service = new KeycloakService();
        $service->updateWhatsappNumber($this->userId, $this->whatsapp_number);

        info('Nomor WhatsApp user ID '.$this->userId.' terverifikasi.' . ' Nomor: '.$this->whatsapp_number);

        session()->flash('success', 'Nomor WhatsApp berhasil diverifikasi.');

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
        return view('livewire.pages.update-whatsapp-number');
    }
}
