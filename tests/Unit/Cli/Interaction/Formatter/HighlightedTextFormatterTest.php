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

use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the HighlightedTextFormatter class.
 */
class HighlightedTextFormatterTest extends TestCase
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
