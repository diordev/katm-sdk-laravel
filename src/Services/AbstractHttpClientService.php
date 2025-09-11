<?php

declare(strict_types=1);

namespace Mkb\KatmSdkLaravel\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

abstract class AbstractHttpClientServiceGPT
{
    protected const AUTH_NONE   = 'none';
    protected const AUTH_BASIC  = 'basic';
    protected const AUTH_BEARER = 'bearer';
    protected const VERSION_SDK = 'KatmSdkLaravel/1.0.0';
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

    /** Doimiy qo‘shimcha headerlar */
    protected array $extraHeaders = [];

    /** Faqat navbatdagi so‘rovga qo‘shiladigan headerlar */
    protected array $extraHeadersOnce = [];

    public function __construct()
    {
        $cfg = (array) config('katm', []);

        $this->baseUrl  = rtrim((string)($cfg['base_url'] ?? ''), '/');
        $this->username = (string)($cfg['username'] ?? '');
        $this->password = (string)($cfg['password'] ?? '');
        $this->timeout  = (int)($cfg['timeout'] ?? 10);

        $this->headers = is_array($cfg['headers'] ?? null)
            ? $cfg['headers']
            : ['Accept' => 'application/json', 'User-Agent' => self::VERSION_SDK];



        $tries   = (int)($cfg['retry']['tries'] ?? 0);
        $sleepMs = (int)($cfg['retry']['sleep_ms'] ?? 0);
        $when    = (array)($cfg['retry']['when'] ?? [429, 500, 502, 503, 504]);
        $this->retry = [
            'tries' => max(0, $tries),
            'sleep_ms' => max(0, $sleepMs),
            'when' => $when
        ];

        // Ixtiyoriy tarmoq opsiyalari
        $connectTimeout = $cfg['connect_timeout'] ?? null; // soniya
        if ($connectTimeout !== null) {
            $this->options['connect_timeout'] = (int) $connectTimeout;
        }
        if (array_key_exists('verify_ssl', $cfg)) {
            // bool yoki sertifikat fayl yo‘li bo‘lishi mumkin
            $this->options['verify'] = $cfg['verify_ssl'];
        }

        // Proxy: to‘liq URL -> bo‘lmasa ENV fallback
        $proxyUrl = $cfg['proxy_url']
            ?? $this->buildProxyUrl(
                $cfg['proxy_proto'] ?? null,
                $cfg['proxy_host']  ?? null,
                $cfg['proxy_port']  ?? null
            );


        $this->options['proxy'] = $proxyUrl;

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

    /** Doimiy qo‘shimcha headerlar qo‘shish (merge) */
    public function withExtraHeaders(array $headers): static
    {
        $this->extraHeaders = array_merge($this->extraHeaders, $headers);
        return $this;
    }

    /** Faqat navbatdagi so‘rovga header qo‘shish */
    public function withExtraHeadersOnce(array $headers): static
    {
        $this->extraHeadersOnce = array_merge($this->extraHeadersOnce, $headers);
        return $this;
    }

    /** HTTP klientini tayyorlaydi */
    protected function client(string $auth = self::AUTH_NONE): PendingRequest
    {
        if ($this->baseUrl === '') {
            throw new RuntimeException("KATM base_url bo'sh. config/katm.php ni to'ldiring.");
        }

        // Headerlarni yig‘ish: base -> persistent -> one-shot
        $headers = array_merge($this->headers, $this->extraHeaders, $this->extraHeadersOnce);

        // Tracing uchun X-Request-ID (app.debug yoki katm.add_request_id yoqilganda)
        $addRequestId = (bool) (config('katm.add_request_id', false) || config('app.debug'));
        if ($addRequestId && ! array_key_exists('X-Request-ID', $headers)) {
            $headers['X-Request-ID'] = (string) Str::uuid();
        }

        $client = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->withHeaders($headers)
            ->withOptions($this->options);

        // Subclass’lar uchun hook
        $client = $this->configureClient($client);

        // Retry (immediate retry ham mumkin; connection-level xatolarni ham qamrab oladi)
        if ($this->retry['tries'] > 0) {
            $whenStatuses = $this->retry['when'];
            $client = $client->retry(
                $this->retry['tries'],
                $this->retry['sleep_ms'],
                function ($exception) use ($whenStatuses): bool {
                    if (method_exists($exception, 'response') && $exception->response()) {
                        return in_array($exception->response()->status(), $whenStatuses, true);
                    }
                    return $exception instanceof ConnectionException
                        || $exception instanceof RequestException;
                }
            );
        }

        // Auth rejimi
        switch ($auth) {
            case self::AUTH_BASIC:
                $client = $client->withBasicAuth($this->username, $this->password);
                break;

            case self::AUTH_BEARER:
                $allowEmpty = (bool) config('katm.allow_empty_bearer', false);
                if (! $this->bearer && ! $allowEmpty) {
                    throw new RuntimeException('Bearer token topilmadi. Avval withBearer() chaqiring yoki login qiling.');
                }
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

    /** POST helper (JSON) */
    protected function post(string $path, array $payload = [], string $auth = self::AUTH_NONE): array
    {
        return $this->requestJson('POST', $path, ['json' => $payload], $auth);
    }

    /** PUT helper (JSON) */
    protected function put(string $path, array $payload = [], string $auth = self::AUTH_NONE): array
    {
        return $this->requestJson('PUT', $path, ['json' => $payload], $auth);
    }

    /** PATCH helper (JSON) */
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

    /** x-www-form-urlencoded POST -> JSON kutadi */
    protected function postForm(string $path, array $payload = [], string $auth = self::AUTH_NONE): array
    {
        $url    = $this->norm($path);
        $client = $this->client($auth)->asForm();

        // one-shot headerlar faqat bitta so‘rovga
        $this->extraHeadersOnce = [];

        $response = $client->post($url, $payload);
        $response->throw();

        $json = $response->json();
        if (! is_array($json)) {
            $status = $response->status();
            $type   = (string) $response->header('Content-Type');
            throw new RuntimeException("Kutilgan JSON massiv emas: POST {$url}; status={$status}; content-type={$type}");
        }
        return $json;
    }

    /** Umumiy yuboruvchi – JSON kutadi */
    private function requestJson(string $method, string $path, array $options = [], string $auth = self::AUTH_NONE): array
    {
        $url    = $this->norm($path);
        $client = $this->client($auth);

        // one-shot headerlar iste’mol qilindi
        $this->extraHeadersOnce = [];

        $response = $client->send($method, $url, $options);
        $response->throw();

        $json = $response->json();
        if (! is_array($json)) {
            $status = $response->status();
            $type   = (string) $response->header('Content-Type');
            throw new RuntimeException("Kutilgan JSON massiv emas: {$method} {$url}; status={$status}; content-type={$type}");
        }
        return $json;
    }

    /** Umumiy yuboruvchi – raw string body qaytaradi */
    private function requestRaw(string $method, string $path, array $options = [], string $auth = self::AUTH_NONE): string
    {
        $url    = $this->norm($path);
        $client = $this->client($auth);

        // one-shot headerlar iste’mol qilindi
        $this->extraHeadersOnce = [];

        $response = $client->send($method, $url, $options);
        $response->throw();
        return (string) $response->body();
    }

    /** Path normalizatsiya: absolyut URL’ni saqlab qoladi */
    private function norm(string $path): string
    {
        $p = trim($path);
        if ($p === '') {
            return '/';
        }
        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            return $p;
        }
        return '/' . ltrim($p, '/');
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

    /** Subclass’lar uchun qo‘shimcha sozlash hook’i */
    protected function configureClient(PendingRequest $client): PendingRequest
    {
        return $client;
    }
}
