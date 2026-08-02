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

use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionNoFormatterException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the NewLine class.
 */
final class NewLineTest extends TestCase
{
    public function testDefaults(): void
    {
        $message = new NewLine();

        self::assertSame("\n", $message->getText());
        self::assertSame("\n", $message->getFormattedText());
        self::assertFalse($message->hasFormatter());
    }

    public function testFormatter(): void
    {
        $formatter = new HighlightedTextFormatter();

        $message = new NewLine(formatter: $formatter);

        self::assertSame("\n", $message->getText());
        self::assertSame($formatter->formatText("\n"), $message->getFormattedText());
        self::assertSame($formatter, $message->getFormatter());
    }

    public function testFormatterThrowsWhenNoneSet(): void
    {
        $this->expectException(CliInteractionNoFormatterException::class);
        $this->expectExceptionMessage('No formatter has been set');

        $message = new NewLine();

        $message->getFormatter();
    }
}
