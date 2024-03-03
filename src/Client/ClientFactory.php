<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Client;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use CloudwatchLogger\Assert\CloudwatchLoggerAssert;
use CloudwatchLogger\Exception\CloudwatchLoggerException;

class ClientFactory
{
    /** @throws CloudwatchLoggerException */
    public function createByConfig(ClientConfig $config): CloudWatchLogsClient
    {
        CloudwatchLoggerAssert::assertClientConfig($config);

        return new CloudWatchLogsClient([
            'credentials' => [
                'key' => $config->key,
                'secret' => $config->secret,
            ],
            'region' => $config->region,
            'version' => $config->version,
        ]);
    }
}
