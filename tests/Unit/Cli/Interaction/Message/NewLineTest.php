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
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the NewLine class.
 */
class NewLineTest extends TestCase
{
    public function testDefaults(): void
    {
        $message = new NewLine();

        self::assertSame("\n", $message->getText());
        self::assertSame("\n", $message->getFormattedText());
        self::assertNull($message->getFormatter());
    }

    public function testFormatter(): void
    {
        $formatter = new HighlightedTextFormatter();

        $message = new NewLine(formatter: $formatter);

        self::assertSame("\n", $message->getText());
        self::assertSame($formatter->formatText("\n"), $message->getFormattedText());
        self::assertSame($formatter, $message->getFormatter());
    }
}
