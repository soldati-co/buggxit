<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $table;

    public function __construct(string $table)
    {
        $this->baseUrl = rtrim(config('services.supabase.url'), '/');
        $this->apiKey  = config('services.supabase.key');

        if (empty($this->baseUrl) || empty($this->apiKey)) {
            throw new \RuntimeException('Supabase configuration missing.');
        }

        $this->table = $table;
    }

    /**
     * Return a pending HTTP request with common headers.
     */
    protected function request()
    {
        return Http::withHeaders([
            'apikey'        => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
            'Prefer'        => 'return=representation',
        ])->timeout(10)            // 10s total timeout
          ->connectTimeout(5)      // 5s connection timeout
          ->retry(2, 100);         // retry twice with 100ms delay
    }

    // -- Query builder methods that return JSON directly --

    public function select(string $columns = '*', array $filters = [])
    {
        $query = array_merge(['select' => $columns], $filters);
        $response = $this->request()->get(
            "{$this->baseUrl}/rest/v1/{$this->table}", $query
        );
        return $response->json() ?? [];
    }

    public function insert(array $data)
    {
        $response = $this->request()->post(
            "{$this->baseUrl}/rest/v1/{$this->table}?select=*", $data
        );
        return $response->json() ?? [];
    }

    public function update(array $data, array $filters)
    {
        $query = http_build_query($filters);
        $response = $this->request()->patch(
            "{$this->baseUrl}/rest/v1/{$this->table}?select=*&{$query}", $data
        );
        return $response->json() ?? [];
    }

    public function delete(array $filters)
    {
        $query = http_build_query($filters);
        $response = $this->request()->delete(
            "{$this->baseUrl}/rest/v1/{$this->table}?select=*&{$query}"
        );
        return $response->json() ?? [];
    }
}