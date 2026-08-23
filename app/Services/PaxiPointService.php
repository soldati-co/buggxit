<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PEP/Paxi don't offer a documented partner API for their store locator --
 * the locator embedded on paxi.co.za is an Angular app (map.paxi.co.za) that
 * simply fetches the entire point network as a static JSON file client-side
 * (found by reading its compiled bundle). We fetch that same file server-side
 * and cache it, rather than hitting it on every checkout page interaction.
 */
class PaxiPointService
{
    private const SOURCE_URL = 'https://map.paxi.co.za/points.json';

    private const CACHE_KEY = 'paxi_points_v1';

    private const CACHE_TTL_HOURS = 24;

    /**
     * A handful of real entries in PEP's own data have inconsistent
     * formatting (missing space, a typo, an alternate spelling) -- normalize
     * both sides of the comparison so those stores aren't silently dropped.
     */
    private const PROVINCE_ALIASES = [
        'KWA ZULU NATAL' => 'KWAZULU NATAL',
        'MPUMAALANGA' => 'MPUMALANGA',
        'NORTHERNCAPE' => 'NORTHERN CAPE',
    ];

    /**
     * @return array<int, array{code: ?string, name: string, address: string, city: ?string, province: ?string, phone: ?string}>
     */
    public function forProvince(string $province): array
    {
        $target = $this->normalizeProvince($province);

        if ($target === '') {
            return [];
        }

        return collect($this->openPoints())
            ->filter(fn (array $point) => $this->normalizeProvince($point['a6'] ?? '') === $target)
            ->map(fn (array $point) => $this->format($point))
            ->sortBy('name')
            ->values()
            ->all();
    }

    public function findByCode(string $code): ?array
    {
        $match = collect($this->openPoints())->first(fn (array $point) => ($point['nc'] ?? null) === $code);

        return $match ? $this->format($match) : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function openPoints(): array
    {
        $points = Cache::remember(self::CACHE_KEY, now()->addHours(self::CACHE_TTL_HOURS), function () {
            try {
                $response = Http::timeout(10)->get(self::SOURCE_URL);

                return $response->successful() ? $response->json() : [];
            } catch (\Throwable $e) {
                Log::warning('Failed to fetch PAXI points feed', ['message' => $e->getMessage()]);

                return [];
            }
        });

        if (! is_array($points)) {
            return [];
        }

        return collect($points)
            ->filter(fn ($point) => is_array($point) && strtolower($point['ns'] ?? '') === 'open')
            ->all();
    }

    private function format(array $point): array
    {
        return [
            'code' => $point['nc'] ?? null,
            'name' => $point['nd'] ?? $point['sn'] ?? 'PEP Point',
            'address' => collect([$point['a1'] ?? null, $point['a2'] ?? null, $point['a4'] ?? null, $point['pc'] ?? null])
                ->filter(fn ($part) => filled($part))
                ->implode(', '),
            'city' => $point['a4'] ?? null,
            'province' => $point['a6'] ?? null,
            'phone' => $point['pn'] ?? null,
        ];
    }

    private function normalizeProvince(string $value): string
    {
        $value = strtoupper(trim(preg_replace('/[\s-]+/', ' ', $value) ?? ''));

        return self::PROVINCE_ALIASES[$value] ?? $value;
    }
}
