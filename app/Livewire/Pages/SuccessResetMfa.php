<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class SuccessResetMfa extends Component
{
    public function render()
    {
        return view('livewire.pages.success-reset-mfa');
    }
}
