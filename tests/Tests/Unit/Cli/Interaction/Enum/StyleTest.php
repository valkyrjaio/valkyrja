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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Enum;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Cli\Interaction\Enum\Style;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class StyleTest extends TestCase
{
    /**
     * @return array<string, array{Style, int}>
     */
    public static function provideDefaults(): array
    {
        return [
            'bold'       => [Style::BOLD, 22],
            'underscore' => [Style::UNDERSCORE, 24],
            'blink'      => [Style::BLINK, 25],
            'inverse'    => [Style::INVERSE, 27],
            'conceal'    => [Style::CONCEAL, 28],
        ];
    }

    public function testCaseValues(): void
    {
        self::assertSame(1, Style::BOLD->value);
        self::assertSame(4, Style::UNDERSCORE->value);
        self::assertSame(5, Style::BLINK->value);
        self::assertSame(7, Style::INVERSE->value);
        self::assertSame(8, Style::CONCEAL->value);
    }

    public function testCasesCount(): void
    {
        self::assertCount(5, Style::cases());
    }

    #[DataProvider('provideDefaults')]
    public function testGetDefaultMatchesEachCase(Style $style, int $expected): void
    {
        self::assertSame($expected, $style->getDefault());
    }
}
