<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Logger;

use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Client\ClientFactory;
use CloudwatchLogger\Handler\HandlerConfig;
use CloudwatchLogger\Handler\HandlerFactory;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __construct(private readonly HandlerFactory $handler, private readonly ClientFactory $client,)
    {
    }

    public function createCloudwatchLogger(
        string $name,
        ClientConfig $clientConfig,
        HandlerConfig $handlerConfig,
    ): LoggerInterface
    {
        $logger = new Logger($name);
        $logger->pushHandler($this->handler->createByClientUsingConfig(
            client: $this->client->createByConfig($clientConfig),
            config: $handlerConfig,
        ));

        return $logger;
    }
}
