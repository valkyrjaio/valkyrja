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

use Valkyrja\Cli\Routing\Provider\ComponentProvider;
use Valkyrja\Cli\Routing\Provider\ServiceProvider;
use Valkyrja\Cli\Server\Command\HelpCommand;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Component service.
 */
final class ComponentProviderTest extends TestCase
{
    public function testGetContainerProvider(): void
    {
        self::assertContains(ServiceProvider::class, ComponentProvider::getContainerProviders());
    }

    public function testGetCliControllers(): void
    {
        self::assertContains(HelpCommand::class, ComponentProvider::getCliControllers());
        self::assertContains(ListBashCommand::class, ComponentProvider::getCliControllers());
        self::assertContains(ListCommand::class, ComponentProvider::getCliControllers());
        self::assertContains(VersionCommand::class, ComponentProvider::getCliControllers());
    }
}
