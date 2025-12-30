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

namespace Valkyrja\Tests\Unit\Cli\Provider;

use Valkyrja\Cli\Command\HelpCommand;
use Valkyrja\Cli\Command\ListBashCommand;
use Valkyrja\Cli\Command\ListCommand;
use Valkyrja\Cli\Command\VersionCommand;
use Valkyrja\Cli\Provider\ComponentProvider;
use Valkyrja\Cli\Interaction\Provider\ServiceProvider as InteractionServiceProvider;
use Valkyrja\Cli\Middleware\Provider\ServiceProvider as MiddlewareServiceProvider;
use Valkyrja\Cli\Routing\Provider\ServiceProvider as RoutingServiceProvider;
use Valkyrja\Cli\Server\Provider\ServiceProvider as ServerServiceProvider;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Component service.
 *
 * @author Melech Mizrachi
 */
class ComponentProviderTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        self::assertContains(InteractionServiceProvider::class, ComponentProvider::getContainerProviders());
        self::assertContains(MiddlewareServiceProvider::class, ComponentProvider::getContainerProviders());
        self::assertContains(RoutingServiceProvider::class, ComponentProvider::getContainerProviders());
        self::assertContains(ServerServiceProvider::class, ComponentProvider::getContainerProviders());
    }

    public function testGetCliControllers(): void
    {
        self::assertContains(HelpCommand::class, ComponentProvider::getCliControllers());
        self::assertContains(ListBashCommand::class, ComponentProvider::getCliControllers());
        self::assertContains(ListCommand::class, ComponentProvider::getCliControllers());
        self::assertContains(VersionCommand::class, ComponentProvider::getCliControllers());
    }
}
