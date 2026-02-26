<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Throwable\Exception\NoFormatterException;
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
        $this->expectException(NoFormatterException::class);
        $this->expectExceptionMessage('No formatter has been set');

        $message = new NewLine();

        $message->getFormatter();
    }
}
