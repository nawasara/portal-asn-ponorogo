<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Services\AppsService;
use Illuminate\Support\Facades\Session;

class Guest extends Component
{
    public AppsService $appsService;
    public $apps = [];
    
    public function mount()
    {
        $service = new AppsService();
        $this->apps = $service->getApps();

        if (auth()->user()) {
            self::getNumber();
        }
    }

    public function getNumber()
    {
        $token = Session::get('keycloak_id_user');

        $service = new \App\Services\KeycloakService();
        $number = $service->getWhatsappNumber($token);
        if (!$number) {
            return redirect()->route('update-whatsapp-number');
        }
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
