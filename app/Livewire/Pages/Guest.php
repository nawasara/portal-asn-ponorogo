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
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
