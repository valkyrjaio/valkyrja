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

namespace Valkyrja\Tests\Unit\Application\Provider;

use Valkyrja\Application\Cli\Command\CacheCommand;
use Valkyrja\Application\Cli\Command\ClearCacheCommand;
use Valkyrja\Application\Provider\CliRouteProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the cli route provider service.
 */
final class CliRouteProviderTest extends TestCase
{
    public function testGetControllerClasses(): void
    {
        $controllers = CliRouteProvider::getControllerClasses();

        self::assertContains(CacheCommand::class, $controllers);
        self::assertContains(ClearCacheCommand::class, $controllers);
    }

    public function testGetRoutes(): void
    {
        self::assertEmpty(CliRouteProvider::getRoutes());
    }
}
