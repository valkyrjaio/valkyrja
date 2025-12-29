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

namespace Valkyrja\Tests\Unit\Http;

use Valkyrja\Http\Client\Provider\ServiceProvider as ClientServiceProvider;
use Valkyrja\Http\ComponentProvider;
use Valkyrja\Http\Message\Provider\ServiceProvider as MessageServiceProvider;
use Valkyrja\Http\Middleware\Provider\ServiceProvider as MiddlewareServiceProvider;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Provider\ServiceProvider as RoutingServiceProvider;
use Valkyrja\Http\Server\Provider\ServiceProvider as ServerServiceProvider;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Component service.
 *
 * @author Melech Mizrachi
 */
class ComponentTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        self::assertContains(ClientServiceProvider::class, ComponentProvider::getContainerProviders());
        self::assertContains(MessageServiceProvider::class, ComponentProvider::getContainerProviders());
        self::assertContains(MiddlewareServiceProvider::class, ComponentProvider::getContainerProviders());
        self::assertContains(RoutingServiceProvider::class, ComponentProvider::getContainerProviders());
        self::assertContains(ServerServiceProvider::class, ComponentProvider::getContainerProviders());
    }

    public function testGetCliControllers(): void
    {
        self::assertContains(ListCommand::class, ComponentProvider::getCliControllers());
    }
}
