<?php

namespace Mkb\KatmSdkLaravel\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class BaseKatmService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected int $timeout;
    protected ?string $bearer = null;

    /** Qo'shimcha HTTP options (masalan proxy) */
    protected array $options = [];

    /** Default headerlar (config/katm.php dagi 'headers') */
    protected array $headers = ['Accept' => 'application/json'];

    public function __construct()
    {
        $config = config('katm', []);

        $this->baseUrl  = rtrim((string)($config['base_url'] ?? ''), '/');
        $this->username = (string)($config['username'] ?? '');
        $this->password = (string)($config['password'] ?? '');
        $this->timeout  = (int)($config['timeout'] ?? 10);
        $this->headers  = is_array($config['headers'] ?? null) ? $config['headers'] : $this->headers;

        // Proxy URL: to'g'ridan-to'g'ri (KATM_PROXY_URL) yoki proto+host+port dan yig'ish
        $proxyUrl = $config['proxy_url']
            ?? $this->buildProxyUrl(
                $config['proxy_proto'] ?? null,
                $config['proxy_host']  ?? null,
                $config['proxy_port']  ?? null
            );

        if (is_string($proxyUrl) && str_contains($proxyUrl, '://')) {
            $this->options['proxy'] = $proxyUrl;
        }
    }

    /** Bearer tokenni tashqaridan berish uchun */
    public function withBearer(?string $token): static
    {
        $this->bearer = $token;
        return $this;
    }

    /** Minimal HTTP klient — Basic yoki Bearer bilan (JSON yuborishni default yoqamiz) */
    protected function client(string $auth = 'bearer', array $extraHeaders = [], bool $asJson = true): PendingRequest
    {
        $client = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->withOptions($this->options)
            ->withHeaders(array_merge($this->headers, $extraHeaders));

        if ($asJson) {
            $client = $client->asJson(); // body JSON bo'lsin (POST/PUT/PATCH uchun qulay)
        }

        if ($auth === 'basic') {
            $client = $client->withBasicAuth($this->username, $this->password);
        } elseif ($auth === 'bearer' && $this->bearer) {
            $client = $client->withToken($this->bearer);
        }

        return $client;
    }

    /** GET (query orqali), JSON natija */
    protected function get(string $path, array $query = [], string $auth = 'bearer'): array
    {
        return $this->client($auth, asJson: false) // GET uchun asJson shart emas
            ->get($this->normalizePath($path), $query)
            ->json();
    }

    /** POST (JSON body), JSON natija */
    protected function post(string $path, array $payload = [], string $auth = 'bearer'): array
    {
        return $this->client($auth, asJson: true)
            ->post($this->normalizePath($path), $payload)
            ->json();
    }

    /** Yordamchilar */
    protected function normalizePath(string $path): string
    {
        return '/' . ltrim($path, '/');
    }

    protected function buildProxyUrl(?string $proto, ?string $host, ?string $port): ?string
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
