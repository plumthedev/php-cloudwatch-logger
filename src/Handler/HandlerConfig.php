<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Handler;

use Monolog\Formatter\FormatterInterface;

class HandlerConfig
{
    private function __construct(
        public readonly string $groupName,
        public readonly string $streamName,
        public readonly int $retentionDays,
        public readonly int $batchSize,
        public readonly FormatterInterface|null $formatter = null,
    )
    {
    }

    public static function create(
        string $groupName,
        string $streamName,
        int $retentionDays = 28,
        int $batchSize = 25,
        FormatterInterface|null $formatter = null,
    ): self
    {
        return new self(
            groupName: $groupName,
            streamName: $streamName,
            retentionDays: $retentionDays,
            batchSize: $batchSize,
            formatter: $formatter,
        );
    }
}
