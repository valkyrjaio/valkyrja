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
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Banner class.
 *
 * @author Melech Mizrachi
 */
class BannerTest extends TestCase
{
    public function testText(): void
    {
        $text      = 'text';
        $formatter = new HighlightedTextFormatter();

        $message = new Message(text: $text, formatter: $formatter);

        $banner = new Banner(message: $message);

        self::assertStringContainsString($text, $banner->getText());
        self::assertStringContainsString($formatter->formatText("    $text    "), $banner->getFormattedText());
    }
}
