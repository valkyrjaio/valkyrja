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

namespace Valkyrja\Tests\Unit\Cli;

use Valkyrja\Cli\Command\HelpCommand;
use Valkyrja\Cli\Command\ListBashCommand;
use Valkyrja\Cli\Command\ListCommand;
use Valkyrja\Cli\Command\VersionCommand;
use Valkyrja\Cli\Component;
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
class ComponentTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        self::assertContains(InteractionServiceProvider::class, Component::getContainerProviders());
        self::assertContains(MiddlewareServiceProvider::class, Component::getContainerProviders());
        self::assertContains(RoutingServiceProvider::class, Component::getContainerProviders());
        self::assertContains(ServerServiceProvider::class, Component::getContainerProviders());
    }

    public function testGetCliControllers(): void
    {
        self::assertContains(HelpCommand::class, Component::getCliControllers());
        self::assertContains(ListBashCommand::class, Component::getCliControllers());
        self::assertContains(ListCommand::class, Component::getCliControllers());
        self::assertContains(VersionCommand::class, Component::getCliControllers());
    }
}
