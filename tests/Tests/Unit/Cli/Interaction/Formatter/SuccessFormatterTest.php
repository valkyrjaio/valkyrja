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
use Valkyrja\Cli\Interaction\Formatter\SuccessFormatter;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the SuccessFormatter class.
 */
class SuccessFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $text = 'text';

        $color    = TextColor::LIGHT_WHITE->value;
        $colorEnd = TextColor::LIGHT_WHITE->getDefault();

        $backgroundColor    = BackgroundColor::GREEN->value;
        $backgroundColorEnd = BackgroundColor::GREEN->getDefault();

        $formatter = new SuccessFormatter();

        self::assertSame("\033[$color;{$backgroundColor}m$text\033[$colorEnd;{$backgroundColorEnd}m", $formatter->formatText($text));
    }
}
