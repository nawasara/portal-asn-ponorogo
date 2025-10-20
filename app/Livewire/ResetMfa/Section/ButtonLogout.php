<?php

namespace App\Livewire\ResetMfa\Section;

use Livewire\Component;
use Livewire\Attributes\On;

class ButtonLogout extends Component
{
    public $showLogout = false;

    #[On('show-logout')]
    public function showLogout()
    {
        $this->showLogout = true;
    }

    public function render()
    {
        return view('livewire.reset-mfa.section.button-logout');
    }
}
