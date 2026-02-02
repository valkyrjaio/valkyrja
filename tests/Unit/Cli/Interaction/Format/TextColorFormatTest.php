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

use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Format\Contract\FormatContract;
use Valkyrja\Cli\Interaction\Format\Format;
use Valkyrja\Cli\Interaction\Format\TextColorFormat;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the TextColorFormat class.
 */
class TextColorFormatTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $format = new TextColorFormat(TextColor::RED);

        self::assertInstanceOf(FormatContract::class, $format);
    }

    public function testExtendsFormat(): void
    {
        $format = new TextColorFormat(TextColor::RED);

        self::assertInstanceOf(Format::class, $format);
    }

    public function testSetState(): void
    {
        $setCode   = (string) TextColor::WHITE->value;
        $unsetCode = (string) TextColor::DEFAULT;

        $format = TextColorFormat::__set_state([
            'setCode'   => $setCode,
            'unsetCode' => $unsetCode,
        ]);

        self::assertSame($setCode, $format->getSetCode());
        self::assertSame($unsetCode, $format->getUnsetCode());
    }

    public function testGetSetCode(): void
    {
        $format = new TextColorFormat(TextColor::RED);

        self::assertSame((string) TextColor::RED->value, $format->getSetCode());
    }

    public function testGetUnsetCode(): void
    {
        $format = new TextColorFormat(TextColor::RED);

        self::assertSame((string) TextColor::DEFAULT, $format->getUnsetCode());
    }

    public function testDifferentColors(): void
    {
        $colors = [
            TextColor::BLACK,
            TextColor::RED,
            TextColor::GREEN,
            TextColor::YELLOW,
            TextColor::BLUE,
            TextColor::MAGENTA,
            TextColor::CYAN,
            TextColor::WHITE,
        ];

        foreach ($colors as $color) {
            $format = new TextColorFormat($color);

            self::assertSame((string) $color->value, $format->getSetCode());
            self::assertSame((string) TextColor::DEFAULT, $format->getUnsetCode());
        }
    }
}
