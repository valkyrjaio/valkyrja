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
use Valkyrja\Container\ContextAwareContainer;
use Valkyrja\Tests\Classes\Container\Service;
use Valkyrja\Tests\Classes\Container\Service2;
use Valkyrja\Tests\Classes\Container\Singleton;
use Valkyrja\Tests\Classes\Container\Singleton2;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ContextAwareContainer service.
 *
 * @author Melech Mizrachi
 */
class ContextAwareContainerTest extends TestCase
{
    public function testService(): void
    {
        $config    = new Config();
        $container = new ContextAwareContainer($config, true);

        $container->bind(Service::class, Service::class);

        $withContext    = $container->withContext(Service::class, 'make');
        $withoutContext = $container->withoutContext();

        $withContext->bind(Service::class, Service2::class);

        self::assertTrue($container->isService(Service::class));
        self::assertInstanceOf(Service::class, $container->get(Service::class));
        self::assertInstanceOf(Service::class, $container->getService(Service::class));

        self::assertTrue($withContext->isService(Service::class));
        self::assertInstanceOf(Service2::class, $withContext->get(Service::class));
        self::assertInstanceOf(Service2::class, $withContext->getService(Service::class));

        self::assertTrue($withoutContext->isService(Service::class));
        self::assertInstanceOf(Service::class, $withoutContext->get(Service::class));
        self::assertInstanceOf(Service::class, $withoutContext->getService(Service::class));
    }

    public function testAlias(): void
    {
        $config    = new Config();
        $container = new ContextAwareContainer($config, true);

        $aliasDefault = 'foo';
        $aliasContext = 'bar';

        $container->bindAlias($aliasDefault, Service::class);
        $container->bind(Service::class, Service::class);

        $withContext    = $container->withContext(Service::class, 'make');
        $withoutContext = $container->withoutContext();

        $withContext->bindAlias($aliasDefault, Service2::class);
        $withContext->bindAlias($aliasContext, Service2::class);
        $withContext->bind(Service2::class, Service2::class);

        self::assertTrue($container->isAlias($aliasDefault));
        // This alias should only exist in the context aware container
        self::assertFalse($container->isAlias($aliasContext));
        self::assertInstanceOf(Service::class, $container->get($aliasDefault));
        self::assertInstanceOf(Service::class, $container->getService($aliasDefault));

        // We added both aliases to ensure that the default alias in the context aware container returned Service2
        // and thus works correctly
        self::assertTrue($withContext->isAlias($aliasDefault));
        self::assertTrue($withContext->isAlias($aliasContext));
        self::assertInstanceOf(Service2::class, $withContext->get($aliasDefault));
        self::assertInstanceOf(Service2::class, $withContext->get($aliasContext));
        self::assertInstanceOf(Service2::class, $withContext->getService($aliasDefault));
        self::assertInstanceOf(Service2::class, $withContext->getService($aliasContext));

        self::assertTrue($withoutContext->isAlias($aliasDefault));
        self::assertFalse($withoutContext->isAlias($aliasContext));
        self::assertInstanceOf(Service::class, $withoutContext->get($aliasDefault));
        self::assertInstanceOf(Service::class, $withoutContext->getService($aliasDefault));
    }

    public function testSingleton(): void
    {
        $config    = new Config();
        $container = new ContextAwareContainer($config, true);

        $container->bindSingleton(Singleton::class, Singleton::class);

        $withContext    = $container->withContext(Singleton::class, 'make');
        $withoutContext = $container->withoutContext();

        $withContext->bindSingleton(Singleton::class, Singleton2::class);

        self::assertTrue($container->isSingleton(Singleton::class));
        self::assertInstanceOf(Singleton::class, $container->get(Singleton::class));
        self::assertInstanceOf(Singleton::class, $container->getSingleton(Singleton::class));

        self::assertTrue($withContext->isSingleton(Singleton::class));
        self::assertInstanceOf(Singleton2::class, $withContext->get(Singleton::class));
        self::assertInstanceOf(Singleton2::class, $withContext->getSingleton(Singleton::class));

        self::assertTrue($withoutContext->isSingleton(Singleton::class));
        self::assertInstanceOf(Singleton::class, $withoutContext->get(Singleton::class));
        self::assertInstanceOf(Singleton::class, $withoutContext->getSingleton(Singleton::class));
    }

    public function testClosure(): void
    {
        $config    = new Config();
        $container = new ContextAwareContainer($config, true);

        $id = 'closureTest';

        $valueDefault = 'foo';
        $valueContext = 'bar';

        $closureDefault = static fn (): string => $valueDefault;
        $closureContext = static fn (): string => $valueContext;

        $container->setClosure($id, $closureDefault);

        $withContext    = $container->withContext('closure');
        $withoutContext = $container->withoutContext();

        $withContext->setClosure($id, $closureContext);

        self::assertTrue($container->isClosure($id));
        self::assertSame($valueDefault, $container->get($id));
        self::assertSame($valueDefault, $container->getClosure($id));

        self::assertTrue($withContext->isClosure($id));
        self::assertSame($valueContext, $withContext->get($id));
        self::assertSame($valueContext, $withContext->getClosure($id));

        self::assertTrue($withoutContext->isClosure($id));
        self::assertSame($valueDefault, $withoutContext->get($id));
        self::assertSame($valueDefault, $withoutContext->getClosure($id));
    }
}
