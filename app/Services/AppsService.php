<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Daftar aplikasi diambil dari katalog yang dikelola di panel Nawasara
 * (nawasara/asn-portal, GET /public/apps) — katalog yang sama dengan peluncur
 * di Akun Kisara. Menambah aplikasi cukup lewat panel, tanpa rilis portal ini.
 *
 * Disimpan di cache beberapa menit supaya tiap kunjungan beranda tidak
 * memanggil Nawasara. Kalau Nawasara tidak terjangkau, yang dipakai adalah
 * daftar terakhir yang berhasil diambil, lalu daftar bawaan di bawah —
 * beranda tanpa daftar aplikasi lebih buruk daripada daftar yang sedikit basi.
 */
class AppsService
{
    private const CACHE_KEY = 'portal-apps';
    private const LAST_GOOD_KEY = 'portal-apps:last-good';

    /**
     * Logo yang sudah ada di public/img, dipakai selama katalog belum punya
     * icon_url untuk aplikasi itu.
     */
    private const LOCAL_ICONS = [
        'simashebat' => 'img/simas.png',
        'supergratifikasi' => 'img/supergratifikasi.png',
        'rakaca' => 'img/rakaca.png',
        'sipras' => 'img/sipras.png',
        'sadap' => 'img/sadap.png',
    ];

    /** Dipakai hanya bila katalog belum pernah berhasil diambil sama sekali. */
    private const FALLBACK = [
        ['slug' => 'simashebat', 'name' => 'Simashebat', 'description' => 'Sistem Informasi Manajemen ASN Pemerintah Kabupaten Ponorogo', 'url' => 'https://simashebat.ponorogo.go.id/login/sso/'],
        ['slug' => 'supergratifikasi', 'name' => 'Supergratifikasi', 'description' => 'Aplikasi pengendalian gratifikasi dilingkungan Pemkab. Ponorogo', 'url' => 'https://supergratifikasi.ponorogo.go.id/login/sso'],
        ['slug' => 'rakaca', 'name' => 'Rakaca', 'description' => 'Etalase Layanan Teknologi Informasi dan Infrastruktur Bidang Aplikasi dan Informatika Dinas Kominfo dan Statistik', 'url' => 'https://rakaca.ponorogo.go.id/login'],
        ['slug' => 'sipras', 'name' => 'Sipras', 'description' => 'Aplikasi pengelolaan data Peta dan Koordinat yang dapat diakses oleh Perangkat Daerah di Lingkungan Pemerintah Kabupaten Ponorogo', 'url' => 'https://sipras.ponorogo.go.id/admin'],
        ['slug' => 'sadap', 'name' => 'Satu Data Ponorogo (Sadap)', 'description' => 'Portal satu data Pemerintah Kabupaten Ponorogo', 'url' => 'https://sadap.ponorogo.go.id/sso'],
    ];

    public function getApps(): array
    {
        $raw = Cache::get(self::CACHE_KEY);

        if ($raw === null) {
            $raw = $this->fetch();

            if ($raw !== null) {
                Cache::put(self::CACHE_KEY, $raw, (int) config('services.nawasara.apps_ttl', 300));
                Cache::forever(self::LAST_GOOD_KEY, $raw);
            } else {
                $raw = Cache::get(self::LAST_GOOD_KEY, self::FALLBACK);
            }
        }

        return array_values(array_filter(array_map(fn ($a) => $this->present($a), $raw)));
    }

    /** null = gagal; daftar kosong dari Nawasara tetap dianggap jawaban sah. */
    private function fetch(): ?array
    {
        $base = rtrim((string) config('services.nawasara.api_url'), '/');

        try {
            $response = Http::acceptJson()->timeout(5)->get($base.'/public/apps');

            if ($response->successful() && is_array($response->json('data'))) {
                return $response->json('data');
            }

            Log::warning('Katalog aplikasi Nawasara menjawab '.$response->status());
        } catch (\Throwable $e) {
            Log::warning('Katalog aplikasi Nawasara tidak terjangkau: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Bentuk yang dipakai blade. Isinya diketik staf di panel, jadi tautan
     * selain http(s) dibuang (href="javascript:..." tidak boleh lolos) dan
     * warna hanya diterima sebagai hex sebelum masuk ke atribut style.
     */
    private function present(mixed $app): ?array
    {
        if (! is_array($app) || empty($app['name']) || ! $this->isHttpUrl($app['url'] ?? null)) {
            return null;
        }

        $slug = (string) ($app['slug'] ?? '');
        $icon = $this->isHttpUrl($app['icon_url'] ?? null)
            ? $app['icon_url']
            : (isset(self::LOCAL_ICONS[$slug]) ? asset(self::LOCAL_ICONS[$slug]) : null);

        $color = $app['color'] ?? null;

        return [
            'slug' => $slug,
            'name' => (string) $app['name'],
            'description' => (string) ($app['description'] ?? ''),
            'category' => $app['category'] ?? null,
            'link' => $app['url'],
            'icon' => $icon,
            'abbreviation' => mb_strtoupper((string) ($app['abbreviation'] ?? mb_substr($app['name'], 0, 2))),
            'color' => is_string($color) && preg_match('/^#[0-9a-fA-F]{3,8}$/', $color) ? $color : '#059669',
            'status' => 'connected',
        ];
    }

    private function isHttpUrl(mixed $url): bool
    {
        return is_string($url) && preg_match('#^https?://#i', $url) === 1;
    }
}
