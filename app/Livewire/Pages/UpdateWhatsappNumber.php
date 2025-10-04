<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Traits\SessionTrait;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class UpdateWhatsappNumber extends Component
{
    use SessionTrait;
    public ?string $whatsapp_number = null;
    public $userId;
    public bool $showOtpForm = false;

    protected $rules = [
        'whatsapp_number' => ['required', 'regex:/^62[0-9]{8,13}$/'],
    ];

    protected $messages = [
        'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
        'whatsapp_number.regex' => 'Format nomor harus diawali 62 dan hanya angka (contoh: 6281234567890).',
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

    public function sendOtp(KeycloakService $keycloak, $resend = false)
    {
        $this->whatsapp_number = $this->whatsapp_number ? : Cache::get($this->getWACacheKey());

        // Validasi nomor
        $this->validateOnly('whatsapp_number');

        $this->showOtpForm = true;
        $this->dispatch('send-otp', waNumber: $this->whatsapp_number);
    }

    #[On('otp-valid')]
    public function verifyOtp()
    {
        // $service = new KeycloakService();
        // $service->updateWhatsappNumber($this->userId, $this->whatsapp_number);

        info('Nomor WhatsApp user ID '.$this->userId.' terverifikasi.' . ' Nomor: '.$this->whatsapp_number);

        session()->flash('success', 'Nomor WhatsApp berhasil diverifikasi.');

        $this->redirect('/');

    }

    public function render()
    {
        return view('livewire.pages.update-whatsapp-number');
    }
}
