<?php

namespace App\Livewire\Pages\PortalDashboard;

use Livewire\Component;

class Index extends Component
{
    public array $apps = [
        [
            'name' => 'E-Arsip',
            'icon' => '📂',
            'description' => 'Sistem pengarsipan elektronik Dinas Kearsipan.',
            'status' => 'connected',
        ],
        [
            'name' => 'E-Surat',
            'icon' => '✉️',
            'description' => 'Aplikasi surat-menyurat internal pemerintah.',
            'status' => 'connected',
        ],
        [
            'name' => 'Absensi Pegawai',
            'icon' => '🕒',
            'description' => 'Sistem kehadiran pegawai Diskominfo.',
            'status' => 'disconnected',
        ],
    ];
    
    public function render()
    {
        return view('livewire.pages.portal-dashboard.index');
    }
}
