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

use Valkyrja\Cli\Interaction\Enum\Style;
use Valkyrja\Cli\Interaction\Format\Contract\FormatContract;
use Valkyrja\Cli\Interaction\Format\Format;
use Valkyrja\Cli\Interaction\Format\StyleFormat;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the StyleFormat class.
 */
class StyleFormatTest extends TestCase
{
    public function testImplementsContract(): void
    {
        $format = new StyleFormat(Style::BOLD);

        self::assertInstanceOf(FormatContract::class, $format);
    }

    public function testExtendsFormat(): void
    {
        $format = new StyleFormat(Style::BOLD);

        self::assertInstanceOf(Format::class, $format);
    }

    public function testSetState(): void
    {
        $setCode   = (string) Style::BOLD->value;
        $unsetCode = (string) Style::DEFAULTS[Style::BOLD->value];

        $format = StyleFormat::__set_state([
            'setCode'   => $setCode,
            'unsetCode' => $unsetCode,
        ]);

        self::assertSame($setCode, $format->getSetCode());
        self::assertSame($unsetCode, $format->getUnsetCode());
    }

    public function testGetSetCode(): void
    {
        $format = new StyleFormat(Style::BOLD);

        self::assertSame((string) Style::BOLD->value, $format->getSetCode());
    }

    public function testGetUnsetCode(): void
    {
        $format = new StyleFormat(Style::BOLD);

        self::assertSame((string) Style::DEFAULTS[Style::BOLD->value], $format->getUnsetCode());
    }

    public function testAllStyles(): void
    {
        $styles = [
            Style::BOLD,
            Style::UNDERSCORE,
            Style::BLINK,
            Style::INVERSE,
            Style::CONCEAL,
        ];

        foreach ($styles as $style) {
            $format = new StyleFormat($style);

            self::assertSame((string) $style->value, $format->getSetCode());
            self::assertSame((string) Style::DEFAULTS[$style->value], $format->getUnsetCode());
        }
    }

    public function testBoldStyle(): void
    {
        $format = new StyleFormat(Style::BOLD);

        self::assertSame('1', $format->getSetCode());
        self::assertSame((string) Style::BOLD_DEFAULT, $format->getUnsetCode());
    }

    public function testUnderscoreStyle(): void
    {
        $format = new StyleFormat(Style::UNDERSCORE);

        self::assertSame('4', $format->getSetCode());
        self::assertSame((string) Style::UNDERSCORE_DEFAULT, $format->getUnsetCode());
    }

    public function testBlinkStyle(): void
    {
        $format = new StyleFormat(Style::BLINK);

        self::assertSame('5', $format->getSetCode());
        self::assertSame((string) Style::BLINK_DEFAULT, $format->getUnsetCode());
    }

    public function testInverseStyle(): void
    {
        $format = new StyleFormat(Style::INVERSE);

        self::assertSame('7', $format->getSetCode());
        self::assertSame((string) Style::INVERSE_DEFAULT, $format->getUnsetCode());
    }

    public function testConcealStyle(): void
    {
        $format = new StyleFormat(Style::CONCEAL);

        self::assertSame('8', $format->getSetCode());
        self::assertSame((string) Style::CONCEAL_DEFAULT, $format->getUnsetCode());
    }
}
