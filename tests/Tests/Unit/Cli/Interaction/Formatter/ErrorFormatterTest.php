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

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Formatter\ErrorFormatter;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ErrorFormatter class.
 */
final class ErrorFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $text = 'text';

        $color    = TextColor::LIGHT_WHITE->value;
        $colorEnd = TextColor::LIGHT_WHITE->getDefault();

        $backgroundColor    = BackgroundColor::RED->value;
        $backgroundColorEnd = BackgroundColor::RED->getDefault();

        $formatter = new ErrorFormatter();

        self::assertSame("\033[$color;{$backgroundColor}m$text\033[$colorEnd;{$backgroundColorEnd}m", $formatter->formatText($text));
    }
}
