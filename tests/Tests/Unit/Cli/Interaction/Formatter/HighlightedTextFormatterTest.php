<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Interaction\Formatter;

use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the HighlightedTextFormatter class.
 */
final class HighlightedTextFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $text = 'text';

        $color    = TextColor::YELLOW->value;
        $colorEnd = TextColor::YELLOW->getDefault();

        $formatter = new HighlightedTextFormatter();

        self::assertSame("\033[{$color}m$text\033[{$colorEnd}m", $formatter->formatText($text));
    }
}
