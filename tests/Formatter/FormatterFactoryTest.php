<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Formatter;

use CloudwatchLogger\Formatter\FormatterFactory;
use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use Tests\CloudwatchLogger\CloudwatchLoggerTestCase;

class FormatterFactoryTest extends CloudwatchLoggerTestCase
{
    public function testCreateDefault(): void
    {
        $factory = new FormatterFactory();
        $this->assertSame(
            sprintf(
                'channel-name: ERROR: Error content %s %s',
                json_encode(['context' => 'content'], JSON_THROW_ON_ERROR),
                json_encode(['extra' => 'content'], JSON_THROW_ON_ERROR),
            ),
            $factory->createDefault()->format(new LogRecord(
                datetime: new DateTimeImmutable(),
                channel: 'channel-name',
                level: Level::Error,
                message: 'Error content',
                context: ['context' => 'content'],
                extra: ['extra' => 'content'],
            )),
        );
    }
}
