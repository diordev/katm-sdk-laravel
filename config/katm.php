<?php

return [
    'base_url' => env('KATM_BASE_URL', 'www.example.com/api'),
    'username' => env('KATM_USERNAME', 'admin'),
    'password' => env('KATM_PASSWORD', 'admin1234'),
    'token_ttl' => env('KATM_TOKEN_TTL', 120),
    'proxy_url' => env('KATM_PROXY_URL'),
    'proxy_proto' => env('KATM_PROXY_PROTO'),
    'proxy_host' => env('KATM_PROXY_HOST'),
    'proxy_port' => env('KATM_PROXY_PORT'),
    'timeout' => env('KATM_TIMEOUT', 10),

    'headers' => [
        'Accept' => 'application/json',
    ],

    'retry' => [
        'times' => env('KATM_RETRY_TIMES', 3),
        'sleep_ms' => env('KATM_RETRY_SLEEP_MS', 200), // millisekund
        'when' => [429, 500, 502, 503, 504],
    ],

];
