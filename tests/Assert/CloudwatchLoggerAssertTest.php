<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Assert;

use CloudwatchLogger\Assert\CloudwatchLoggerAssert;
use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Exception\CloudwatchLoggerException;
use CloudwatchLogger\Handler\HandlerConfig;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class CloudwatchLoggerAssertTest extends CloudwatchLoggerTestCase
{
    /** @return iterable<ClientConfig> */
    // phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
    public static function clientConfigExamples(): iterable
    {
        return [
            'correct config' => [
                'config' => self::clientConfig(),
                'exception' => null,
            ],
            'missing api key' => [
                'config' => self::clientConfig(key: ''),
                'exception' => CloudwatchLoggerException::class,
            ],
            'missing api secret' => [
                'config' => self::clientConfig(secret: ''),
                'exception' => CloudwatchLoggerException::class,
            ],
            'missing region' => [
                'config' => self::clientConfig(region: ''),
                'exception' => CloudwatchLoggerException::class,
            ],
            'missing version' => [
                'config' => self::clientConfig(version: ''),
                'exception' => CloudwatchLoggerException::class,
            ],
        ];
    }

    /** @return iterable<HandlerConfig> */
    // phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
    public static function handlerConfigExamples(): iterable
    {
        return [
            'correct config' => [
                'config' => self::handlerConfig(),
                'exception' => null,
            ],
            'missing group name' => [
                'config' => self::handlerConfig(groupName: ''),
                'exception' => CloudwatchLoggerException::class,
            ],
            'missing stream name' => [
                'config' => self::handlerConfig(streamName: ''),
                'exception' => CloudwatchLoggerException::class,
            ],
            'negative batch size' => [
                'config' => self::handlerConfig(batchSize: - 1),
                'exception' => CloudwatchLoggerException::class,
            ],
            'negative retention days' => [
                'config' => self::handlerConfig(retentionDays: - 1),
                'exception' => CloudwatchLoggerException::class,
            ],
            'positive batch size' => [
                'config' => self::handlerConfig(batchSize: 1),
                'exception' => null,
            ],
            'positive retention day' => [
                'config' => self::handlerConfig(retentionDays: 1),
                'exception' => null,
            ],
            'provides formatter' => [
                'config' => self::handlerConfig(formatter: new LineFormatter()),
                'exception' => null,
            ],
            'zero batch size' => [
                'config' => self::handlerConfig(batchSize: 0),
                'exception' => CloudwatchLoggerException::class,
            ],
            'zero retention days' => [
                'config' => self::handlerConfig(retentionDays: 0),
                'exception' => CloudwatchLoggerException::class,
            ],
        ];
    }

    private static function clientConfig(
        string $key = 'cloudwatch api key',
        string $secret = 'cloudwatch api secret',
        string $region = 'eu-central-1',
        string $version = '1.0.0',
    ): ClientConfig
    {
        return ClientConfig::create(key: $key, secret: $secret, region: $region, version: $version);
    }

    private static function handlerConfig(
        string $groupName = 'group name',
        string $streamName = 'stream name',
        int $retentionDays = 14,
        int $batchSize = 10,
        FormatterInterface|null $formatter = null,
    ): HandlerConfig
    {
        return HandlerConfig::create(
            groupName: $groupName,
            streamName: $streamName,
            retentionDays: $retentionDays,
            batchSize: $batchSize,
            formatter: $formatter,
        );
    }

    /** @dataProvider clientConfigExamples */
    public function testAssertClientConfig(ClientConfig $config, string|null $expectedException): void
    {
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }

        CloudwatchLoggerAssert::assertClientConfig($config);
        $this->assertNull($expectedException);
    }

    /** @dataProvider handlerConfigExamples */
    public function testAssertHandlerConfig(HandlerConfig $config, string|null $expectedException): void
    {
        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }

        CloudwatchLoggerAssert::assertHandlerConfig($config);
        $this->assertNull($expectedException);
    }
}
