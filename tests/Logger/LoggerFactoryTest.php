<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Logger;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Client\ClientFactory;
use CloudwatchLogger\Handler\HandlerConfig;
use CloudwatchLogger\Handler\HandlerFactory;
use CloudwatchLogger\Logger\LoggerFactory;
use Mockery;
use Monolog\Logger;
use PhpNexus\Cwh\Handler\CloudWatch;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class LoggerFactoryTest extends CloudwatchLoggerTestCase
{
    public function testCreate(): void
    {
        $handlerConfig = HandlerConfig::create('group', 'stream');
        $clientConfig = ClientConfig::create('key', 'secret', 'eu-central-1');
        $cloudwatchClient = Mockery::mock(CloudWatchLogsClient::class);
        $handler = Mockery::mock(CloudWatch::class);

        $factory = new LoggerFactory(
            handler: Mockery::mock(HandlerFactory::class)
                ->shouldReceive('createByClientUsingConfig')
                ->with($cloudwatchClient, $handlerConfig)
                ->once()
                ->andReturn($handler)
                ->getMock(),
            client: Mockery::mock(ClientFactory::class)
                ->shouldReceive('createByConfig')
                ->once()
                ->with($clientConfig)
                ->andReturn($cloudwatchClient)
                ->getMock(),
        );

        $logger = $factory->createCloudwatchLogger('cloudwatch logger', $clientConfig, $handlerConfig);

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('cloudwatch logger', $logger->getName());
        $this->assertCount(1, $logger->getHandlers());
        $this->assertInstanceOf(CloudWatch::class, $logger->popHandler());
        $this->assertEmpty($logger->getHandlers());
    }
}
