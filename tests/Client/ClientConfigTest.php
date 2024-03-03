<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Client;

use CloudwatchLogger\Client\ClientConfig;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class ClientConfigTest extends CloudwatchLoggerTestCase
{
    public function testCreate(): void
    {
        $config = ClientConfig::create('api key', 'secret key', 'selected region', 'selected version');

        $this->assertSame('api key', $config->key);
        $this->assertSame('secret key', $config->secret);
        $this->assertSame('selected region', $config->region);
        $this->assertSame('selected version', $config->version);
    }
}
