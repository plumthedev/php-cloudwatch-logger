<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Formatter;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;

class FormatterFactory
{
    public function createDefault(): FormatterInterface
    {
        return new LineFormatter(
            format: '%channel%: %level_name%: %message% %context% %extra%',
            dateFormat: null,
            allowInlineLineBreaks: false,
            ignoreEmptyContextAndExtra: true,
        );
    }
}
