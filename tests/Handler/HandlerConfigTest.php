<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Handler;

use CloudwatchLogger\Handler\HandlerConfig;
use Monolog\Formatter\LineFormatter;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class HandlerConfigTest extends CloudwatchLoggerTestCase
{
    public function testCreate(): void
    {
        $config = HandlerConfig::create(
            groupName: 'group name',
            streamName: 'stream name',
            retentionDays: 28,
            batchSize: 300,
            formatter: new LineFormatter(),
        );

        $this->assertSame('group name', $config->groupName);
        $this->assertSame('stream name', $config->streamName);
        $this->assertSame(28, $config->retentionDays);
        $this->assertSame(300, $config->batchSize);
        $this->assertNotNull($config->formatter);
    }
}
