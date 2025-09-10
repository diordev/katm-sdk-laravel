<?php

declare(strict_types=1);

namespace Mkb\KatmSdkLaravel\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

abstract class AbstractHttpClientService
{
    protected const AUTH_NONE   = 'none';
    protected const AUTH_BASIC  = 'basic';
    protected const AUTH_BEARER = 'bearer';

    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected int    $timeout;
    /** @var array<string,string> */
    protected array  $headers;
    /** @var array{times:int,sleep_ms:int,when:int[]} */
    protected array  $retry;
    /** @var array<string,mixed> */
    protected array  $options = [];

    /** Bearer token (ixtiyoriy) */
    protected ?string $bearer = null;

    /** Qo‘shimcha headerlar (run-time qo‘shiladi) */
    protected array $extraHeaders = [];

    public function __construct()
    {
        $cfg = (array) config('katm', []);

        $this->baseUrl  = rtrim((string)($cfg['base_url'] ?? ''), '/');
        $this->username = (string)($cfg['username'] ?? '');
        $this->password = (string)($cfg['password'] ?? '');
        $this->timeout  = (int)($cfg['timeout'] ?? 10);

        $this->headers  = is_array($cfg['headers'] ?? null)
            ? $cfg['headers']
            : ['Accept' => 'application/json'];

        $times   = (int)($cfg['retry']['times'] ?? 0);
        $sleepMs = (int)($cfg['retry']['sleep_ms'] ?? 0);
        $when    = (array)($cfg['retry']['when'] ?? [429, 500, 502, 503, 504]);
        $this->retry   = ['times' => max(0, $times), 'sleep_ms' => max(0, $sleepMs), 'when' => $when];

        $proxyUrl = $cfg['proxy_url']
            ?? $this->buildProxyUrl(
                $cfg['proxy_proto'] ?? null,
                $cfg['proxy_host']  ?? null,
                $cfg['proxy_port']  ?? null
            );

        if (is_string($proxyUrl) && str_contains($proxyUrl, '://')) {
            $this->options['proxy'] = $proxyUrl;
        }
    }

    /** Bearer token ulash/yangilash */
    public function withBearer(?string $token): static
    {
        $this->bearer = $token ?: null;
        return $this;
    }

    /** Bearer tokenni olib tashlash */
    public function withoutBearer(): static
    {
        $this->bearer = null;
        return $this;
    }

    /** Qo‘shimcha headerlar qo‘shish (mavjudlarini ustiga yozadi) */
    public function withExtraHeaders(array $headers): static
    {
        $this->extraHeaders = array_merge($this->extraHeaders, $headers);
        return $this;
    }

    /** HTTP clientni tayyorlaydi — HAR DOIM PendingRequest qaytaradi */
    protected function client(string $auth = self::AUTH_NONE): PendingRequest
    {
        if ($this->baseUrl === '') {
            throw new RuntimeException('KATM base_url bo‘sh. config/katm.php ni tekshiring.');
        }

        $headers = array_merge($this->headers, $this->extraHeaders);


        $client = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->withHeaders($headers)
            ->withOptions($this->options);

        // Retry sozlamalari
        if ($this->retry['times'] > 0 && $this->retry['sleep_ms'] > 0) {
            $whenStatuses = $this->retry['when'];
            $client = $client->retry(
                $this->retry['times'],
                $this->retry['sleep_ms'],
                function ($exception) use ($whenStatuses): bool {
                    if (method_exists($exception, 'response') && $exception->response()) {
                        return in_array($exception->response()->status(), $whenStatuses, true);
                    }
                    // Connection level xatolar uchun ham qayta urinish mumkin
                    return $exception instanceof RequestException;
                }
            );
        }

        // Auth rejimi
        switch ($auth) {
            case self::AUTH_BASIC:
                $client = $client->withBasicAuth($this->username, $this->password);
                break;

            case self::AUTH_BEARER:
                if ($this->bearer) {
                    $client = $client->withToken($this->bearer);
                }
                break;

            case self::AUTH_NONE:
            default:
                // auth yo‘q
                break;
        }

        return $client;
    }

    /** GET helper */
    protected function get(string $path, array $query = [], string $auth = self::AUTH_NONE): array
    {
        return $this->requestJson('GET', $path, ['query' => $query], $auth);
    }

    /** POST helper */
    protected function post(string $path, array $payload = [], string $auth = self::AUTH_NONE): array
    {
        return $this->requestJson('POST', $path, ['json' => $payload], $auth);
    }

    /** PUT helper */
    protected function put(string $path, array $payload = [], string $auth = self::AUTH_NONE): array
    {
        return $this->requestJson('PUT', $path, ['json' => $payload], $auth);
    }

    /** PATCH helper */
    protected function patch(string $path, array $payload = [], string $auth = self::AUTH_NONE): array
    {
        return $this->requestJson('PATCH', $path, ['json' => $payload], $auth);
    }

    /** DELETE helper (query yoki body bilan) */
    protected function delete(string $path, array $payloadOrQuery = [], string $auth = self::AUTH_NONE, bool $asBody = false): array
    {
        $options = $asBody ? ['json' => $payloadOrQuery] : ['query' => $payloadOrQuery];
        return $this->requestJson('DELETE', $path, $options, $auth);
    }

    /**
     * Umumiy yuboruvchi – JSON kutadi.
     * Xato bo‘lsa tushunarli exception tashlaydi.
     */
    protected function requestJson(string $method, string $path, array $options = [], string $auth = self::AUTH_NONE): array
    {
        $url = $this->norm($path);

        $response = $this->client($auth)->send($method, $url, $options);

        // HTTP xatolarni tashlaydi (4xx/5xx)
        $response->throw();

        $json = $response->json();

        if (!is_array($json)) {
            throw new RuntimeException("Kutilgan JSON massiv emas: {$method} {$url}");
        }

        return $json;
    }

    /** Path normalizatsiya */
    private function norm(string $path): string
    {
        return '/' . ltrim($path, '/');
    }

    /** Proxy URL yig‘ish */
    private function buildProxyUrl(?string $proto, ?string $host, ?string $port): ?string
    {
        $proto = trim((string)$proto);
        $host  = trim((string)$host);
        $port  = trim((string)$port);

        if ($proto !== '' && $host !== '' && $port !== '') {
            return "{$proto}://{$host}:{$port}";
        }
        return null;
    }
}
