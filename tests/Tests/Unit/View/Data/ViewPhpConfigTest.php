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
use Valkyrja\View\Data\Contract\ViewPhpConfigContract;
use Valkyrja\View\Data\ViewPhpConfig;

final class ViewPhpConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(ViewPhpConfigContract::class, new ViewPhpConfig());
    }

    public function testDefaults(): void
    {
        $config = new ViewPhpConfig();

        self::assertSame('/resources/views', $config->phpPath);
        self::assertSame('.phtml', $config->phpFileExtension);
        self::assertSame([], $config->phpPaths);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new ViewPhpConfig(
            phpPath: '/storage',
            phpFileExtension: '.test.phtml',
            phpPaths: ['test' => '/test'],
        );

        self::assertSame('/storage', $config->phpPath);
        self::assertSame('.test.phtml', $config->phpFileExtension);
        self::assertSame(['test' => '/test'], $config->phpPaths);
    }
}
