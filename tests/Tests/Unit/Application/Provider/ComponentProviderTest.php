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

use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\CliRouteProvider;
use Valkyrja\Application\Provider\ComponentProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Component service.
 */
final class ComponentProviderTest extends TestCase
{
    public function testGetCliProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertContains(CliRouteProvider::class, ComponentProvider::getCliProviders($app));
    }

    public function testGetContainerProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(ComponentProvider::getContainerProviders($app));
    }

    public function testGetEventProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(ComponentProvider::getEventProviders($app));
    }

    public function testGetHttpProviders(): void
    {
        $app = self::createStub(ApplicationContract::class);

        self::assertEmpty(ComponentProvider::getHttpProviders($app));
    }
}
