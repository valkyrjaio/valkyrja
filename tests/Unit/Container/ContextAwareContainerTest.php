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

namespace Valkyrja\Tests\Unit\Container;

use Valkyrja\Container\Config;
use Valkyrja\Container\Managers\Container;
use Valkyrja\Container\Managers\ContextAwareContainer;
use Valkyrja\Tests\Classes\Container\Service;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ContextAwareContainer service.
 *
 * @author Melech Mizrachi
 */
class ContextAwareContainerTest extends TestCase
{
    public function testWithContext(): void
    {
        $config     = new Config();
        $container  = new ContextAwareContainer($config, true);
        $container2 = new ContextAwareContainer($config, true);

        $container->bind(Service::class, Service::class);
        $container->setSingleton(Container::class, $container);

        $withContext    = $container->withContext(Service::class, 'make');
        $withoutContext = $container->withoutContext();

        $withContext->setSingleton(Container::class, $container2);

        self::assertSame($container, $container->get(Container::class));
        self::assertSame($container2, $withContext->get(Container::class));
        self::assertSame($container, $withoutContext->get(Container::class));
    }
}
