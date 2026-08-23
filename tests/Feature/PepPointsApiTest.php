<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PepPointsApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_returns_points_for_the_requested_province(): void
    {
        Http::fake(['map.paxi.co.za/*' => Http::response([
            ['nc' => 'A0001', 'sn' => 'PEP SANDTON', 'nd' => 'PEP SANDTON', 'a1' => 'SHOP 1', 'a4' => 'SANDTON', 'a6' => 'GAUTENG', 'pc' => '2196', 'ns' => 'open'],
            ['nc' => 'A0002', 'sn' => 'PEP DURBAN', 'nd' => 'PEP DURBAN', 'a1' => 'SHOP 2', 'a4' => 'DURBAN', 'a6' => 'KWAZULU-NATAL', 'pc' => '4001', 'ns' => 'open'],
        ], 200)]);

        $response = $this->getJson(route('api.pep-points.index', ['province' => 'Gauteng']));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.code', 'A0001');
    }

    public function test_province_is_required(): void
    {
        $response = $this->getJson(route('api.pep-points.index'));

        $response->assertStatus(422);
    }
}
