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

namespace Valkyrja\Tests\Unit\View\Data;

use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\View\Data\Contract\ViewTwigConfigContract;
use Valkyrja\View\Data\ViewTwigConfig;

final class ViewTwigConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(ViewTwigConfigContract::class, new ViewTwigConfig());
    }

    public function testDefaults(): void
    {
        $config = new ViewTwigConfig();

        self::assertSame([], $config->twigPaths);
        self::assertSame([], $config->twigExtensions);
        self::assertSame('/storage/views', $config->twigCompiledPath);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new ViewTwigConfig(
            twigPaths: ['test' => '/test'],
            twigExtensions: [],
            twigCompiledPath: '/storage',
        );

        self::assertSame(['test' => '/test'], $config->twigPaths);
        self::assertSame([], $config->twigExtensions);
        self::assertSame('/storage', $config->twigCompiledPath);
    }
}
