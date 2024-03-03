<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger;

use Mockery;
use Monolog\Test\TestCase;

abstract class CloudwatchLoggerTestCase extends TestCase
{
    public function tearDown(): void
    {
        $container = Mockery::getContainer();

        if ($container !== null) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }
}
