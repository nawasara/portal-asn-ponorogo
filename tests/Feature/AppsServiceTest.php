<?php

namespace Tests\Feature;

use App\Services\AppsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AppsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config(['services.nawasara.api_url' => 'https://nawasara.test/api/v1/asn-portal']);
    }

    private function catalog(array $apps): void
    {
        Http::fake(['nawasara.test/*' => Http::response(['data' => $apps])]);
    }

    public function test_apps_come_from_the_nawasara_catalog(): void
    {
        $this->catalog([
            ['slug' => 'nawasara', 'name' => 'Nawasara', 'description' => 'Superapps', 'url' => 'https://nawasara.ponorogo.go.id/sso/redirect', 'abbreviation' => 'NW', 'color' => '#147176', 'icon_url' => null],
        ]);

        $apps = (new AppsService)->getApps();

        $this->assertCount(1, $apps);
        $this->assertSame('Nawasara', $apps[0]['name']);
        $this->assertSame('https://nawasara.ponorogo.go.id/sso/redirect', $apps[0]['link']);
        $this->assertNull($apps[0]['icon']);
        $this->assertSame('NW', $apps[0]['abbreviation']);
        $this->assertSame('#147176', $apps[0]['color']);
        Http::assertSent(fn ($r) => $r->url() === 'https://nawasara.test/api/v1/asn-portal/public/apps');
    }

    public function test_known_apps_without_an_icon_url_use_the_bundled_logo(): void
    {
        $this->catalog([['slug' => 'simashebat', 'name' => 'Simashebat', 'url' => 'https://simashebat.ponorogo.go.id/login/sso/', 'icon_url' => null]]);

        $this->assertStringEndsWith('img/simas.png', (new AppsService)->getApps()[0]['icon']);
    }

    public function test_unsafe_links_and_colors_from_the_panel_are_dropped(): void
    {
        $this->catalog([
            ['slug' => 'x', 'name' => 'Jahat', 'url' => 'javascript:alert(1)'],
            ['slug' => 'y', 'name' => 'Warna', 'url' => 'https://y.test', 'color' => 'red;background:url(x)', 'icon_url' => 'data:image/svg+xml,...'],
        ]);

        $apps = (new AppsService)->getApps();

        $this->assertCount(1, $apps);
        $this->assertSame('#059669', $apps[0]['color']);
        $this->assertNull($apps[0]['icon']);
    }

    public function test_catalog_is_cached_between_requests(): void
    {
        $this->catalog([['slug' => 'a', 'name' => 'A', 'url' => 'https://a.test']]);

        (new AppsService)->getApps();
        (new AppsService)->getApps();

        Http::assertSentCount(1);
    }

    public function test_last_good_catalog_is_used_when_nawasara_is_down(): void
    {
        $this->catalog([['slug' => 'a', 'name' => 'A', 'url' => 'https://a.test']]);
        (new AppsService)->getApps();

        Cache::forget('portal-apps');
        Http::fake(['nawasara.test/*' => Http::response('', 503)]);

        $this->assertSame('A', (new AppsService)->getApps()[0]['name']);
    }

    public function test_bundled_list_is_used_when_the_catalog_was_never_reached(): void
    {
        Http::fake(['nawasara.test/*' => Http::response('', 503)]);

        $names = array_column((new AppsService)->getApps(), 'name');

        $this->assertContains('Simashebat', $names);
    }
}
