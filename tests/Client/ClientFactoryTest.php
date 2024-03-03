<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Client;

use Aws\Credentials\Credentials;
use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Client\ClientFactory;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class ClientFactoryTest extends CloudwatchLoggerTestCase
{
    public function testCreateByConfig(): void
    {
        $factory = new ClientFactory();
        $client = $factory->createByConfig(ClientConfig::create(
            key: 'api key',
            secret: 'secret key',
            region: 'eu-central-1',
            version: '2014-03-28',
        ));

        $client->getCredentials()->then(function (Credentials $credentials): void {
            $this->assertSame('api key', $credentials->getAccessKeyId());
            $this->assertSame('secret key', $credentials->getSecretKey());
        });
        $this->assertSame('eu-central-1', $client->getRegion());
        $this->assertSame('2014-03-28', $client->getApi()->getApiVersion());
    }
}
