<?php

namespace App\Livewire\SharedComponents;

use App\Traits\SessionTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Topbar extends Component
{
    use SessionTrait;

    /**
     * Lifecycle method yang dijalankan saat komponen di-mount.
     * Memeriksa status sesi Keycloak dan memicu event modal jika tidak aktif.
     *
     * @return void
     */
    public function mount(): void
    {
        // Pastikan user sudah login sebelum melakukan validasi Keycloak
        if (!Auth::check()) {
            return;
        }

        // Ambil status sesi Keycloak dari trait
        $keycloakSession = $this->checkKeycloakSession();

        // Ambil data nomor telepon
        $this->getNumber();

        // Jika sesi tidak aktif, kirim event untuk menampilkan modal sesi
        if (empty($keycloakSession['active'])) {
            $this->dispatch('set-modal-session', show: true);
        }
    }

    public function render()
    {
        return view('livewire.shared-components.topbar');
    }
}
