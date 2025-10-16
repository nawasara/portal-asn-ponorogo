<?php

namespace App\Livewire\Dashboard\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Traits\SessionTrait;

/**
 * Komponen Livewire yang menangani tampilan modal sesi Keycloak.
 *
 * Tujuan utama komponen ini adalah memastikan bahwa sesi pengguna di Keycloak masih aktif.
 * Jika sesi tidak aktif, modal akan ditampilkan dan pengguna otomatis dikeluarkan (logout).
 *
 * Alur logika:
 * - Ketika event `set-modal-session` diterima, fungsi `modalSession()` dipanggil.
 * - Fungsi ini memeriksa status Keycloak dan menentukan apakah modal harus tampil.
 * - Jika sesi tidak aktif, pengguna akan dilogout melalui `logoutApp()`.
 */
class SessionModal extends Component
{
    use SessionTrait;

    public bool $showModal = false;

    /**
     * Lifecycle hook Livewire yang dijalankan saat komponen di-mount.
     * 
     * Digunakan untuk memastikan modal ditutup secara default saat awal.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->modalSession(null);
    }

    /**
     * Render tampilan modal sesi Keycloak.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.dashboard.modal.session-modal');
    }

    /**
     * Event listener Livewire.
     * 
     * Menerima event `set-modal-session` dari komponen lain seperti Dashboard Index.
     * Ketika event diterima, modal akan ditampilkan jika sesi Keycloak tidak aktif.
     *
     * @param  bool|null  $show  Status apakah modal harus ditampilkan.
     * @return void
     */
    #[On('set-modal-session')]
    public function modalSession(?bool $show = null): void
    {
        // Jika event dikirim dengan parameter true, langsung tampilkan modal dan logout
        if ($show === true) {
            $this->showModal = true;
            $this->logoutApp();
        }

        // Jika user terautentikasi, periksa status sesi Keycloak
        if (Auth::check()) {
            $keycloakSession = $this->checkKeycloakSession(); // berasal dari SessionTrait
            $this->getNumber(); // update data user

            // Jika sesi aktif, modal ditutup. Jika tidak, logout dan tampilkan modal.
            if (!empty($keycloakSession['active'])) {
                $this->showModal = false;
            } else {
                $this->logoutApp();
                $this->showModal = true;
            }
        } else {
            // Jika user tidak login, pastikan modal tertutup
            $this->showModal = false;
        }
    }

    /**
     * Melakukan logout dari aplikasi dengan memanggil fungsi logout dari SessionTrait.
     *
     * @return void
     */
    public function logoutApp(): void
    {
        $this->logoutLaravel();
    }
}
