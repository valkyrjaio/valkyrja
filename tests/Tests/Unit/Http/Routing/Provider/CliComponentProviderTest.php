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

namespace Valkyrja\Tests\Unit\Http\Routing\Provider;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Routing\Provider\CliRouteProvider;
use Valkyrja\Http\Routing\Provider\CliServiceProvider;
use Valkyrja\Http\Routing\Provider\ComponentProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Component service.
 */
final class CliComponentProviderTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertContains(CliServiceProvider::class, ComponentProvider::getContainerProviders($app));
    }

    public function testGetCliProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertContains(CliRouteProvider::class, ComponentProvider::getCliProviders($app));
    }
}
