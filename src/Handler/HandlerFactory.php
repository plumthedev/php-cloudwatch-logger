<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Handler;

use Aws\CloudWatchLogs\CloudWatchLogsClient as Client;
use CloudwatchLogger\Assert\CloudwatchLoggerAssert;
use CloudwatchLogger\Exception\CloudwatchLoggerException;
use CloudwatchLogger\Formatter\FormatterFactory;
use Monolog\Handler\HandlerInterface;
use PhpNexus\Cwh\Handler\CloudWatch as CloudwatchHandler;

class HandlerFactory
{
    public function __construct(private readonly FormatterFactory $formatter )
    {
    }

    /** @throws CloudwatchLoggerException */
    public function createByClientUsingConfig(Client $client, HandlerConfig $config): HandlerInterface
    {
        CloudwatchLoggerAssert::assertHandlerConfig($config);
        $handler = new CloudwatchHandler(
            client: $client,
            group: $config->groupName,
            stream: $config->streamName,
            retention: $config->retentionDays,
            batchSize: $config->batchSize,
        );

        return $handler->setFormatter(
            formatter: $config->formatter ?? $this->formatter->createDefault(),
        );
    }
}
