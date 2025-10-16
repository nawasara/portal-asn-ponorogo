<?php

namespace App\Livewire\Dashboard\Section;

use Livewire\Component;
use App\Traits\SessionTrait;
use App\Services\AppsService;

class AppList extends Component
{
    use SessionTrait;
    public AppsService $appsService;
    public $apps = [];

    public $query = '';

    public function mount()
    {
        $service = new AppsService();
        $this->apps = $service->getApps();

        if ($this->query) {
            $q = strtolower($this->query);
            $apps = $apps->filter(fn($a) => str_contains(strtolower($a['title'] . ' ' . $a['desc']), $q));
        }

        if (auth()->user()) {
            self::checkKeycloakSession(); // ada di trait
            self::getNumber();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.section.app-list');
    }
}
