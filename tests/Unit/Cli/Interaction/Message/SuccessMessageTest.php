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

use Valkyrja\Cli\Interaction\Formatter\SuccessFormatter;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the SuccessMessage class.
 *
 * @author Melech Mizrachi
 */
class SuccessMessageTest extends TestCase
{
    public function testText(): void
    {
        $text      = 'text';
        $formatter = new SuccessFormatter();

        $message = new SuccessMessage(text: $text);

        self::assertSame($text, $message->getText());
        self::assertSame($formatter->formatText($text), $message->getFormattedText());
        self::assertInstanceOf(SuccessFormatter::class, $message->getFormatter());
    }
}
