<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Handler;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use CloudwatchLogger\Formatter\FormatterFactory;
use CloudwatchLogger\Handler\HandlerConfig;
use CloudwatchLogger\Handler\HandlerFactory;
use Mockery;
use Monolog\Formatter\FormatterInterface;
use PhpNexus\Cwh\Handler\CloudWatch;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class HandlerFactoryTest extends CloudwatchLoggerTestCase
{
    public function testCreateByClientUsingConfigWithFormatter(): void
    {
        $factory = new HandlerFactory(
            formatter: Mockery::mock(FormatterFactory::class)
                ->shouldReceive('createDefault')
                ->once()
                ->andReturn(Mockery::mock(FormatterInterface::class))
                ->getMock(),
        );
        $handler = $factory->createByClientUsingConfig(
            client: Mockery::mock(CloudWatchLogsClient::class),
            config: HandlerConfig::create(
                groupName: 'group name',
                streamName: 'stream name',
                retentionDays: 21,
                batchSize: 1,
            ),
        );

        $this->assertInstanceOf(CloudWatch::class, $handler);
    }

    public function testCreateByClientUsingConfigWithoutFormatter(): void
    {
        $factory = new HandlerFactory(
            formatter: Mockery::mock(FormatterFactory::class)
                ->shouldNotReceive('createDefault')
                ->getMock(),
        );
        $handler = $factory->createByClientUsingConfig(
            client: Mockery::mock(CloudWatchLogsClient::class),
            config: HandlerConfig::create(
                groupName: 'group name',
                streamName: 'stream name',
                retentionDays: 21,
                batchSize: 1,
                formatter: Mockery::mock(FormatterInterface::class),
            ),
        );

        $this->assertInstanceOf(CloudWatch::class, $handler);
    }
}
