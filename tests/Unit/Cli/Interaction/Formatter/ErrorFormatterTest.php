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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Formatter;

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Formatter\ErrorFormatter;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ErrorFormatter class.
 *
 * @author Melech Mizrachi
 */
class ErrorFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $text = 'text';

        $color    = TextColor::LIGHT_WHITE->value;
        $colorEnd = TextColor::DEFAULT;

        $backgroundColor    = BackgroundColor::RED->value;
        $backgroundColorEnd = BackgroundColor::DEFAULT;

        $formatter = new ErrorFormatter();

        self::assertSame("\033[$color;{$backgroundColor}m$text\033[$colorEnd;{$backgroundColorEnd}m", $formatter->formatText($text));
    }
}
