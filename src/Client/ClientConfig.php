<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Client;

class ClientConfig
{
    private function __construct(
        public readonly string $key,
        public readonly string $secret,
        public readonly string $region,
        public readonly string $version,
    )
    {
    }

    public static function create(string $key, string $secret, string $region, string $version = 'latest',): self
    {
        return new self(key: $key, secret: $secret, region: $region, version: $version);
    }
}
