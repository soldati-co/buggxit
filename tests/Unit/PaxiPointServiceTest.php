<?php

namespace Tests\Unit;

use App\Services\PaxiPointService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaxiPointServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The 'array' cache driver (see phpunit.xml) persists for the whole
        // test run, not per-method -- flush so each test's Http::fake() is
        // actually exercised instead of silently hitting another test's cache.
        Cache::flush();
    }

    private function fakePoints(): array
    {
        return [
            ['nc' => 'A0001', 'sn' => 'PEP SANDTON', 'nd' => 'PEP SANDTON', 'a1' => 'SHOP 1', 'a2' => '1 MAIN RD', 'a4' => 'SANDTON', 'a6' => 'GAUTENG', 'pn' => '0110000000', 'pc' => '2196', 'ns' => 'open'],
            ['nc' => 'A0002', 'sn' => 'PEP SOWETO', 'nd' => 'PEP SOWETO', 'a1' => 'SHOP 2', 'a2' => '2 MAIN RD', 'a4' => 'SOWETO', 'a6' => 'GAUTENG', 'pn' => '0110000001', 'pc' => '1818', 'ns' => 'open'],
            ['nc' => 'A0003', 'sn' => 'PEP CLOSED', 'nd' => 'PEP CLOSED', 'a1' => 'SHOP 3', 'a2' => '3 MAIN RD', 'a4' => 'SANDTON', 'a6' => 'GAUTENG', 'pn' => '0110000002', 'pc' => '2196', 'ns' => 'CLOSED'],
            ['nc' => 'A0004', 'sn' => 'PEP DURBAN', 'nd' => 'PEP DURBAN', 'a1' => 'SHOP 4', 'a2' => '4 MAIN RD', 'a4' => 'DURBAN', 'a6' => 'KWA-ZULU NATAL', 'pn' => '0110000003', 'pc' => '4001', 'ns' => 'open'],
        ];
    }

    public function test_it_returns_only_open_points_matching_the_province(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response($this->fakePoints(), 200)]);

        $points = app(PaxiPointService::class)->forProvince('Gauteng');

        $this->assertCount(2, $points);
        $this->assertEqualsCanonicalizing(['A0001', 'A0002'], array_column($points, 'code'));
    }

    public function test_it_normalizes_known_province_spelling_variants(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response($this->fakePoints(), 200)]);

        // PEP's own data has "KWA-ZULU NATAL" for this record; the checkout
        // form sends "KwaZulu-Natal" -- both must resolve to the same bucket.
        $points = app(PaxiPointService::class)->forProvince('KwaZulu-Natal');

        $this->assertCount(1, $points);
        $this->assertSame('A0004', $points[0]['code']);
    }

    public function test_it_caches_the_feed_and_does_not_refetch(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response($this->fakePoints(), 200)]);

        app(PaxiPointService::class)->forProvince('Gauteng');
        app(PaxiPointService::class)->forProvince('Gauteng');

        Http::assertSentCount(1);
    }

    public function test_find_by_code_returns_a_formatted_point(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response($this->fakePoints(), 200)]);

        $point = app(PaxiPointService::class)->findByCode('A0002');

        $this->assertNotNull($point);
        $this->assertSame('PEP SOWETO', $point['name']);
        $this->assertStringContainsString('SOWETO', $point['address']);
    }

    public function test_find_by_code_returns_null_for_a_closed_or_unknown_point(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response($this->fakePoints(), 200)]);

        $this->assertNull(app(PaxiPointService::class)->findByCode('A0003'));
        $this->assertNull(app(PaxiPointService::class)->findByCode('DOES-NOT-EXIST'));
    }

    public function test_it_returns_an_empty_list_when_the_feed_is_unreachable(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response('', 500)]);

        $points = app(PaxiPointService::class)->forProvince('Gauteng');

        $this->assertSame([], $points);
    }
}
