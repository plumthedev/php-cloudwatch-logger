<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Assert;

use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Exception\CloudwatchLoggerException;
use CloudwatchLogger\Handler\HandlerConfig;
use Webmozart\Assert\Assert;

// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
class CloudwatchLoggerAssert extends Assert
{
    /** @throws CloudwatchLoggerException */
    public static function assertClientConfig(ClientConfig $config): void
    {
        self::notEmpty(
            $config->key,
            'The AWS Cloudwatch API `key` provided in credentials is empty. Please ensure you have a valid Cloudwatch API key.',
        );
        self::notEmpty(
            $config->secret,
            'The AWS Cloudwatch API `secret` provided in credentials is empty. Please ensure you have a valid Cloudwatch API secret key.',
        );
        self::notEmpty(
            $config->region,
            'The AWS Cloudwatch `region` provided in credentials is empty. Please ensure you include a valid AWS region in your credentials e.g. us-west-2',
        );
        self::notEmpty(
            $config->version,
            'The AWS Cloudwatch API `version` provided in credentials is empty. This field is necessary to specify the version of the AWS SDK to use.',
        );
    }

    /** @throws CloudwatchLoggerException */
    public static function assertHandlerConfig(HandlerConfig $config): void
    {
        self::notEmpty(
            $config->groupName,
            'Provided AWS Cloudwatch `group name` is empty. Please ensure you have a valid Cloudwatch group name.',
        );
        self::notEmpty(
            $config->streamName,
            'Provided AWS Cloudwatch `stream name` is empty. Please ensure you have a valid Cloudwatch stream name.',
        );
        self::greaterThanEq(
            $config->retentionDays,
            1,
            'AWS Cloudwatch `retention days` have to be greater or equal than 1. Please ensure you have a valid Cloudwatch retention name.',
        );
        self::greaterThanEq(
            $config->batchSize,
            1,
            'AWS Cloudwatch `batch size` have to be greater or equal than 1. Please ensure you have a valid Cloudwatch batch size.',
        );
    }

    /**
     * @param string $message
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected static function reportInvalidArgument($message): never
    {
        throw new CloudwatchLoggerException($message);
    }
}
