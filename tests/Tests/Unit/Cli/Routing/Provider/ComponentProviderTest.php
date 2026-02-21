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

namespace Valkyrja\Tests\Unit\Cli\Routing\Provider;

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Cli\Routing\Provider\CliRouteProvider;
use Valkyrja\Cli\Routing\Provider\ComponentProvider;
use Valkyrja\Cli\Routing\Provider\ServiceProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Component service.
 */
final class ComponentProviderTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertContains(ServiceProvider::class, ComponentProvider::getContainerProviders($app));
    }

    public function testGetCliProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        $providers = ComponentProvider::getCliProviders($app);

        self::assertContains(CliRouteProvider::class, $providers);
    }
}
