<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Logger;

use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Handler\HandlerConfig;
use Monolog\Formatter\FormatterInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-type CloudwatchLoggerConfigArray array{
 *      name: string|null,
 *      region: string|null,
 *      version: string|null,
 *      credentials: array{
 *          key: string,
 *          secret: string,
 *      },
 *      group_name: string,
 *      stream_name: string,
 *      retention_days: int|null,
 *      batch_size: int|null,
 *      formatter: class-string<FormatterInterface>|null,
 * }
 */
class PhpCloudwatchLogger
{
    public function __construct(private readonly LoggerFactory $factory, private readonly ContainerInterface $container)
    {
    }

    private function tryToCreateFormatter(string $formatter): FormatterInterface|null
    {
        $formatterInstance = null;

        if (class_exists($formatter) ) {
            $formatterInstance = $this->container->get($formatter);
        }

        if ($formatterInstance instanceof FormatterInterface) {
            return $formatterInstance;
        }

        return null;
    }

    /** @param CloudwatchLoggerConfigArray $config */
    public function __invoke(array $config): LoggerInterface
    {
        return $this->factory->createCloudwatchLogger(
            name: $config['name'] ?? 'Cloudwatch Logger',
            clientConfig: ClientConfig::create(
                key: $config['credentials']['key'],
                secret: $config['credentials']['secret'],
                region: $config['region'] ?? 'eu-central-1',
                version: $config['version'] ?? 'latest',
            ),
            handlerConfig: HandlerConfig::create(
                groupName: $config['group_name'],
                streamName: $config['stream_name'],
                retentionDays: $config['retention_days'] ?? 14,
                batchSize: $config['batch_size'] ?? 25,
                formatter: $this->tryToCreateFormatter($config['formatter'] ?? ''),
            ),
        );
    }
}
