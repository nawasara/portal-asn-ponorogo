<?php

namespace App\Livewire\UpdateWhatsappNumber;

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


class Index extends Component
{
    public function render()
    {
        return view('livewire.update-whatsapp-number.index');
    }
}
