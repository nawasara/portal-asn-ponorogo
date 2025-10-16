<?php

namespace App\Livewire\Dashboard\Section;

use Livewire\Attributes\Computed;
use Livewire\Component;

class Faq extends Component
{
    public function render()
    {
        return view('livewire.dashboard.section.faq');
    }

    #[Computed]
    public function availableFaq()
    {
        return [
            [
                'id' => 'sso-mfa',
                'title' => 'Apa itu Single Sign On dan Multi-Factor Authentication ?',
                'description' => 'SSO, singkatan dari Single Sign-On (atau Masuk Tunggal), adalah sebuah sistem otentikasi yang memungkinkan pengguna mengakses banyak aplikasi dengan hanya satu kali masuk menggunakan satu set kredensial. Dengan adanya SSO ini ASN Ponorogo dapat membuka layanan milik Pemerintah Kabupaten Ponorogo tanpa berganti - ganti akun.
                <br>
                MFA adalah singkatan dari Multi-Factor Authentication (Autentikasi Multifaktor), sebuah metode keamanan yang memerlukan dua atau lebih langkah verifikasi untuk masuk ke akun'
            ],
            [
                'id' => 'portal-asn',
                'title' => 'Apa itu Portal ASN Ponorogo?',
                'description' => 'Portal ASN Ponorogo adalah aplikasi portal milik Pemerintah Ponorogo yang berisi layanan ASN. Hal ini memudahkan ASN untuk mengakses layanan milik daerah yang menggunakan SSO'
            ],
            [
                'id' => 'kisara-sso',
                'title' => 'Apa itu Kisara?',
                'description' => 'Kisara adalah nama aplikasi SSO milik Pemerintah Kabupaten Ponrogo yang dikelola oleh Dinas Kominfo dan Statistik Kabupaten Ponorogo'
            ],
            [
                'id' => 'data-simas',
                'title' => 'Mengapa menggunakan akun Simas Hebat?',
                'description' => 'Simas Hebat merupakan Sistem Informasi Kepegawaian yang dimiliki Pemerintah Kabupaten Ponorogo. Seluruh data ASN berada pada SIMAS HEBAT, sehingga untuk memudahkan ASN melakukan Login, kami menggunakan data pada SIMAS HEBAT'
            ],
            [
                'id' => 'lupa-password',
                'title' => 'Saya tidak dapat login menggunakan akun SIMAS HEBAT?',
                'description' => 'Silahkan koordinasi dengan BKPSDM untuk kendala lupa password
                <br>
                Bisa juga dengan mengakses laman berikut:
                <br>
                <br>
                <a href="https://simashebat.ponorogo.go.id/reset-password/" target="_blank">LUPA PASSWORD SIMAS HEBAT</a>
                <br>
                <br>
                Mohon pastikan anda dapat mengakses email dan email anda tidak penuh untuk dapat menerima password pemulihan'
            ],
            [
                'id' => 'invalid-credentials',
                'title' => 'Muncul invalid_credentials pada halaman SSO',
                'description' => 'Error ini muncul karena NIP/Password anda tidak sesuai. Mohon periksa kembali NIP/Password anda'
            ],
            [
                'id' => 'kode-otp-salah',
                'title' => 'Muncul OTP tidak valid pada halaman scan Kode QR ',
                'description' => 'Lakukan pengecekan pengaturan waktu pada smartphone. Aktifkan set waktu otomatis/gunakan waktu jaringan atau yang serupa
                <br>
                <br>
                Silahkan menghapus OTP dengan nama "Kisara ASN Ponorogo" dengan cara geser ke kiri, lalu konfirmasi hapus'
            ],
        ];
    }
}
