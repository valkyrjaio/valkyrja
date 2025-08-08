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
use Valkyrja\Cli\Interaction\Enum\Style;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Formatter class.
 *
 * @author Melech Mizrachi
 */
class FormatterTest extends TestCase
{
    public function testDefaults(): void
    {
        $text = 'text';

        $formatter = new Formatter();

        self::assertNull($formatter->getTextColor());
        self::assertNull($formatter->getBackgroundColor());
        self::assertNull($formatter->getStyle());

        self::assertSame($text, $formatter->formatText($text));
    }

    public function testStyle(): void
    {
        $text     = 'text';
        $style    = Style::BOLD->value;
        $styleEnd = Style::DEFAULTS[$style];

        $formatter = new Formatter(style: Style::BOLD);

        self::assertSame("\033[{$style}m$text\033[{$styleEnd}m", $formatter->formatText($text));

        $formatter2 = $formatter->withStyle(Style::UNDERSCORE);
        $style2     = Style::UNDERSCORE->value;
        $style2End  = Style::DEFAULTS[$style2];

        self::assertNotSame($formatter, $formatter2);
        self::assertSame("\033[{$style2}m$text\033[{$style2End}m", $formatter2->formatText($text));
    }

    public function testTextColor(): void
    {
        $text     = 'text';
        $color    = TextColor::YELLOW->value;
        $colorEnd = TextColor::DEFAULT;

        $formatter = new Formatter(textColor: TextColor::YELLOW);

        self::assertSame("\033[{$color}m$text\033[{$colorEnd}m", $formatter->formatText($text));

        $color2    = TextColor::GREEN->value;
        $color2End = TextColor::DEFAULT;

        $formatter2 = $formatter->withTextColor(TextColor::GREEN);

        self::assertNotSame($formatter, $formatter2);
        self::assertSame("\033[{$color2}m$text\033[{$color2End}m", $formatter2->formatText($text));
    }

    public function testBackgroundColor(): void
    {
        $text     = 'text';
        $color    = BackgroundColor::YELLOW->value;
        $colorEnd = BackgroundColor::DEFAULT;

        $formatter = new Formatter(backgroundColor: BackgroundColor::YELLOW);

        self::assertSame("\033[{$color}m$text\033[{$colorEnd}m", $formatter->formatText($text));

        $color2    = BackgroundColor::BLACK->value;
        $color2End = BackgroundColor::DEFAULT;

        $formatter2 = $formatter->withBackgroundColor(BackgroundColor::BLACK);

        self::assertNotSame($formatter, $formatter2);
        self::assertSame("\033[{$color2}m$text\033[{$color2End}m", $formatter2->formatText($text));
    }

    public function testAll(): void
    {
        $text = 'text';

        $style    = Style::BOLD->value;
        $styleEnd = Style::DEFAULTS[$style];

        $color    = TextColor::YELLOW->value;
        $colorEnd = TextColor::DEFAULT;

        $backgroundColor    = BackgroundColor::YELLOW->value;
        $backgroundColorEnd = BackgroundColor::DEFAULT;

        $formatter = new Formatter(
            textColor: TextColor::YELLOW,
            backgroundColor: BackgroundColor::YELLOW,
            style: Style::BOLD
        );

        self::assertSame("\033[$color;$backgroundColor;{$style}m$text\033[$colorEnd;$backgroundColorEnd;{$styleEnd}m", $formatter->formatText($text));
    }
}
