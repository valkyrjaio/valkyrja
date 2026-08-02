<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Formatter\ErrorFormatter;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ErrorMessage class.
 */
final class ErrorMessageTest extends TestCase
{
    public function testText(): void
    {
        $text      = 'text';
        $formatter = new ErrorFormatter();

        $message = new ErrorMessage(text: $text);

        self::assertSame($text, $message->getText());
        self::assertSame($formatter->formatText($text), $message->getFormattedText());
        self::assertInstanceOf(ErrorFormatter::class, $message->getFormatter());
    }
}
