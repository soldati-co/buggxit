<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
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
     *
     * Retries are only safe for idempotent reads. select() uses
     * retryingRequest(); insert()/update()/delete() use this without
     * retrying so a slow-but-successful write is never resubmitted.
     */
    protected function request(): PendingRequest
    {
        return Http::withHeaders([
            'apikey'        => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
            'Prefer'        => 'return=representation',
        ])->timeout(10)            // 10s total timeout
          ->connectTimeout(5);     // 5s connection timeout
    }

    /**
     * Same as request(), but with retries enabled — only safe for GET.
     */
    protected function retryingRequest(): PendingRequest
    {
        return $this->request()->retry(2, 100); // retry twice with 100ms delay
    }

    /**
     * Translate a simple ['column' => 'value'] filter map into PostgREST's
     * expected `column=op.value` syntax. If a value already contains an
     * operator prefix (e.g. 'gt.10'), it's passed through unchanged.
     */
    protected function applyFilters(array $filters): array
    {
        $query = [];
        foreach ($filters as $column => $value) {
            $query[$column] = (is_string($value) && preg_match('/^[a-z]+\./', $value))
                ? $value
                : 'eq.' . $value;
        }
        return $query;
    }

    /**
     * Guard against an unfiltered write hitting every row in the table.
     */
    protected function assertHasFilters(array $filters): void
    {
        if (empty($filters)) {
            throw new \InvalidArgumentException(
                'Refusing to run an unfiltered update/delete against Supabase table "' . $this->table . '".'
            );
        }
    }

    // -- Query builder methods that return JSON directly --

    public function select(string $columns = '*', array $filters = [])
    {
        $query = array_merge(['select' => $columns], $this->applyFilters($filters));
        $response = $this->retryingRequest()->get(
            "{$this->baseUrl}/rest/v1/{$this->table}", $query
        );
        return $response->throw()->json() ?? [];
    }

    public function insert(array $data)
    {
        $response = $this->request()->post(
            "{$this->baseUrl}/rest/v1/{$this->table}?select=*", $data
        );
        return $response->throw()->json() ?? [];
    }

    public function update(array $data, array $filters)
    {
        $this->assertHasFilters($filters);
        $query = http_build_query($this->applyFilters($filters));
        $response = $this->request()->patch(
            "{$this->baseUrl}/rest/v1/{$this->table}?select=*&{$query}", $data
        );
        return $response->throw()->json() ?? [];
    }

    public function delete(array $filters)
    {
        $this->assertHasFilters($filters);
        $query = http_build_query($this->applyFilters($filters));
        $response = $this->request()->delete(
            "{$this->baseUrl}/rest/v1/{$this->table}?select=*&{$query}"
        );
        return $response->throw()->json() ?? [];
    }
}
