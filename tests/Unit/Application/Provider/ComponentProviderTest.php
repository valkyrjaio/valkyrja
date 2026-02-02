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
use Valkyrja\Application\Provider\ComponentProvider;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Component service.
 */
class ComponentProviderTest extends TestCase
{
    public function testGetCliControllers(): void
    {
        self::assertContains(CacheCommand::class, ComponentProvider::getCliControllers());
        self::assertContains(ClearCacheCommand::class, ComponentProvider::getCliControllers());
    }

    public function testGetContainerProviders(): void
    {
        self::assertEmpty(ComponentProvider::getContainerProviders());
    }

    public function testGetEventListeners(): void
    {
        self::assertEmpty(ComponentProvider::getEventListeners());
    }

    public function testGetHttpControllers(): void
    {
        self::assertEmpty(ComponentProvider::getHttpControllers());
    }
}
