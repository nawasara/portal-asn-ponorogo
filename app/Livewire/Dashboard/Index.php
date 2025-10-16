<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

/**
 * Komponen utama untuk halaman Dashboard.
 */
class Index extends Component
{
    /**
     * Render tampilan utama dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
