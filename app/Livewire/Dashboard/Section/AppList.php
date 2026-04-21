<?php

namespace App\Livewire\Dashboard\Section;

use Livewire\Component;
use Livewire\Attributes\Computed;
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

    #[Computed]
    public function filteredApps()
    {
        $q = strtolower(trim($this->query));

        if ($q === '') {
            return $this->apps;
        }

        return collect($this->apps)
            ->filter(fn ($app) =>
                str_contains(strtolower($app['name']), $q) ||
                str_contains(strtolower($app['description'] ?? ''), $q)
            )
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.dashboard.section.app-list');
    }
}
