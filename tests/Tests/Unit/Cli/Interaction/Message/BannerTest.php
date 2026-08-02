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
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Banner class.
 */
final class BannerTest extends TestCase
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
