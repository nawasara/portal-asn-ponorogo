<?php

namespace App\Livewire\Pages\PortalDashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Index extends Component
{
    public $apps = [];
    public function mount()
    {
        $this->apps = $apps = [
            [
                'name' => 'Simashebat',
                'icon' => asset("img/simas.png"),
                'icon_type' => 'image',
                'description' => 'Panduan penggunaan aplikasi dan layanan yang disediakan',
                'status' => 'Coming Soon',
                'link' => 'https://simashebat.ponorogo.go.id/login'
            ],
            [
                'name' => 'Supergratifikasi',
                'icon' => asset("img/supergratifikasi.png"),
                'icon_type' => 'image',
                'description' => 'Aplikasi pengendalian gratifikasi dilingkungan Pemkab. Ponorogo',
                'status' => 'connected',
                'link' => 'https://supergratifikasi.ponorogo.go.id/login/sso'
            ],
            [
                'name' => 'Rakaca',
                'icon' => asset("img/rakaca.ico"),
                'icon_type' => 'image',
                'description' => 'Etalase Layanan Teknologi Informasi dan Infrastruktur Bidang Aplikasi dan Informatika Dinas Kominfo dan Statistik',
                'status' => 'connected',
                'link' => 'https://rakaca.ponorogo.go.id/login'
            ],
            
            [
                'name' => 'Sipras',
                'icon' => asset("img/sipras.png"),
                'icon_type' => 'image',
                'description' => 'Aplikasi pengelolaan data Peta dan Koordinat yang dapat diakses oleh Perangkat Daerah di Lingkungan Pemerintah Kabupaten Ponorogo',
                'status' => 'connected',
                'link' => 'https://sipras.ponorogo.go.id/admin'
            ],
            
            
            [
                'name' => 'Satu Data Ponorogo (Sadap)',
                'icon' => asset("img/sadap.png"),
                'icon_type' => 'image',
                'description' => 'Portal satu data Pemerintah Kabupaten Ponorogo',
                'status' => 'comming soon',
                'link' => 'https://sadap.ponorogo.go.id/login'
            ],
        ];
    }

    public function render()
    {
        $token = Session::get('keycloak_id_user');

        return view('livewire.pages.portal-dashboard.index');
    }

    public function getNumber()
    {
        $token = Session::get('keycloak_id_user');

        $service = new \App\Services\KeycloakService();
        $number = $service->getWhatsappNumber($token);
        if (!$number) {
            $service->updateWhatsappNumber($token, 628123456789);
        }

        return response()->json([
            'whatsapp_number' => $number
        ]);
    }
}
