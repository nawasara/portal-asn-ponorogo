<?php

namespace App\Livewire\Dashboard\Section;

use Livewire\Component;
use App\Traits\SessionTrait;
use App\Services\AppsService;

class AppList extends Component
{
    public $apps = [];

    public $query = '';

    public function mount()
    {
        $service = new AppsService();
        $this->apps = $service->getApps();
    }

    public function render()
    {
        return view('livewire.dashboard.section.app-list');
    }
}
