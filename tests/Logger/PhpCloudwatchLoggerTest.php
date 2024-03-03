<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Logger;

use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Handler\HandlerConfig;
use CloudwatchLogger\Logger\LoggerFactory;
use CloudwatchLogger\Logger\PhpCloudwatchLogger;
use Hamcrest\Core\IsEqual;
use Mockery;
use Mockery\MockInterface;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class PhpCloudwatchLoggerTest extends CloudwatchLoggerTestCase
{
    /** @return iterable<string> */
    // phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
    public static function loggerConfigExamples(): iterable
    {
        $formatter = new LineFormatter();

        return [
            'fallback is working' => [
                'client key' => 'api_key',
                'client region' => 'eu-central-1',
                'client secret' => 'api_secret',
                'client version' => 'latest',
                'config' => [
                    'credentials' => [
                        'key' => 'api_key',
                        'secret' => 'api_secret',
                    ],
                    'group_name' => 'logs group',
                    'stream_name' => 'logs stream',
                ],
                'container' => Mockery::mock(ContainerInterface::class)
                    ->shouldNotReceive('get')
                    ->getMock(),
                'formatter' => null,
                'handler batch' => 25,
                'handler days' => 14,
                'handler group' => 'logs group',
                'handler stream' => 'logs stream',
                'name' => 'Cloudwatch Logger',
            ],
            'full configuration is working' => [
                'client key' => 'abc-key-123',
                'client region' => 'eu-central-1',
                'client secret' => 'cba-secret-321',
                'client version' => '2014-03-28',
                'config' => [
                    'batch_size' => 10,
                    'credentials' => [
                        'key' => 'abc-key-123',
                        'secret' => 'cba-secret-321',
                    ],
                    'formatter' => LineFormatter::class,
                    'group_name' => 'MyLogsGroup',
                    'name' => 'MyCloudWatchLogger',
                    'region' => 'eu-central-1',
                    'retention_days' => 3,
                    'stream_name' => 'MyStreamName',
                    'version' => '2014-03-28',
                ],
                'container' => Mockery::mock(ContainerInterface::class)
                    ->shouldReceive('get')
                    ->with(LineFormatter::class)
                    ->andReturn($formatter)
                    ->getMock(),
                'formatter' => $formatter,
                'handler batch' => 10,
                'handler days' => 3,
                'handler group' => 'MyLogsGroup',
                'handler stream' => 'MyStreamName',
                'name' => 'MyCloudWatchLogger',
            ],
        ];
    }

    /**
     * @param array<string> $config
     * @dataProvider loggerConfigExamples
     */
    public function testCreateLogger(
        string $clientKey,
        string $clientRegion,
        string $clientSecret,
        string $clientVersion,
        array $config,
        MockInterface|ContainerInterface $container,
        FormatterInterface|null $handlerFormatter,
        int $handlerBatch,
        int $handlerDays,
        string $handlerGroup,
        string $handlerStream,
        string $name,
    ): void
    {
        $expectedLogger = Mockery::mock(LoggerInterface::class);

        $logger = new PhpCloudwatchLogger(
            factory: Mockery::mock(LoggerFactory::class)
                ->shouldReceive('createCloudwatchLogger')
                ->with(
                    $name,
                    IsEqual::equalTo(ClientConfig::create(
                        key: $clientKey,
                        secret: $clientSecret,
                        region: $clientRegion,
                        version: $clientVersion,
                    )),
                    IsEqual::equalTo(HandlerConfig::create(
                        groupName: $handlerGroup,
                        streamName: $handlerStream,
                        retentionDays: $handlerDays,
                        batchSize: $handlerBatch,
                        formatter: $handlerFormatter,
                    )),
                )
                ->once()
                ->andReturn($expectedLogger)
                ->getMock(),
            container: $container,
        );

        $this->assertSame($expectedLogger, $logger($config));
    }
}
