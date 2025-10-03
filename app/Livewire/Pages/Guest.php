<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Traits\SessionTrait;
use App\Services\AppsService;
use Illuminate\Support\Facades\Session;

class Guest extends Component
{
    use SessionTrait;

    public AppsService $appsService;
    public $apps = [];
    
    public function mount()
    {
        $service = new AppsService();
        $this->apps = $service->getApps();

        if (auth()->user()) {
            self::checkKeycloakSession(); // ada di trait
            self::getNumber();
        }
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
