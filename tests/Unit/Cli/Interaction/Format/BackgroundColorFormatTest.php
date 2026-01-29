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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Format;

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Cli\Interaction\Format\BackgroundColorFormat;
use Valkyrja\Cli\Interaction\Format\Contract\FormatContract;
use Valkyrja\Cli\Interaction\Format\Format;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the BackgroundColorFormat class.
 */
class BackgroundColorFormatTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $format = new BackgroundColorFormat(BackgroundColor::RED);

        self::assertInstanceOf(FormatContract::class, $format);
    }

    public function testExtendsFormat(): void
    {
        $format = new BackgroundColorFormat(BackgroundColor::RED);

        self::assertInstanceOf(Format::class, $format);
    }

    public function testGetSetCode(): void
    {
        $format = new BackgroundColorFormat(BackgroundColor::RED);

        self::assertSame((string) BackgroundColor::RED->value, $format->getSetCode());
    }

    public function testGetUnsetCode(): void
    {
        $format = new BackgroundColorFormat(BackgroundColor::RED);

        self::assertSame((string) BackgroundColor::DEFAULT, $format->getUnsetCode());
    }

    public function testDifferentColors(): void
    {
        $colors = [
            BackgroundColor::BLACK,
            BackgroundColor::RED,
            BackgroundColor::GREEN,
            BackgroundColor::YELLOW,
            BackgroundColor::BLUE,
            BackgroundColor::MAGENTA,
            BackgroundColor::CYAN,
            BackgroundColor::WHITE,
        ];

        foreach ($colors as $color) {
            $format = new BackgroundColorFormat($color);

            self::assertSame((string) $color->value, $format->getSetCode());
            self::assertSame((string) BackgroundColor::DEFAULT, $format->getUnsetCode());
        }
    }
}
