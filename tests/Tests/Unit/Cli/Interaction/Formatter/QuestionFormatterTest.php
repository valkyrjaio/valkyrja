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
use Valkyrja\Cli\Interaction\Formatter\QuestionFormatter;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the QuestionFormatter class.
 */
final class QuestionFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $text = 'text';

        $color    = TextColor::MAGENTA->value;
        $colorEnd = TextColor::MAGENTA->getDefault();

        $formatter = new QuestionFormatter();

        self::assertSame("\033[{$color}m$text\033[{$colorEnd}m", $formatter->formatText($text));
    }
}
