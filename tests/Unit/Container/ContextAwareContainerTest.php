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

use Valkyrja\Container\ContextAwareContainer;
use Valkyrja\Tests\Classes\Container\Service2Class;
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Classes\Container\Singleton2Class;
use Valkyrja\Tests\Classes\Container\SingletonClass;
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
        $container = new ContextAwareContainer();

        $container->bind(ServiceClass::class, ServiceClass::class);

        $withContext    = $container->withContext(ServiceClass::class, 'make');
        $withoutContext = $container->withoutContext();

        $withContext->bind(ServiceClass::class, Service2Class::class);

        self::assertTrue($container->isService(ServiceClass::class));
        self::assertInstanceOf(ServiceClass::class, $container->get(ServiceClass::class));
        self::assertInstanceOf(ServiceClass::class, $container->getService(ServiceClass::class));

        self::assertTrue($withContext->isService(ServiceClass::class));
        self::assertInstanceOf(Service2Class::class, $withContext->get(ServiceClass::class));
        self::assertInstanceOf(Service2Class::class, $withContext->getService(ServiceClass::class));

        self::assertTrue($withoutContext->isService(ServiceClass::class));
        self::assertInstanceOf(ServiceClass::class, $withoutContext->get(ServiceClass::class));
        self::assertInstanceOf(ServiceClass::class, $withoutContext->getService(ServiceClass::class));
    }

    public function testAlias(): void
    {
        $container = new ContextAwareContainer();

        $aliasDefault = 'foo';
        $aliasContext = 'bar';

        $container->bindAlias($aliasDefault, ServiceClass::class);
        $container->bind(ServiceClass::class, ServiceClass::class);

        $withContext    = $container->withContext(ServiceClass::class, 'make');
        $withoutContext = $container->withoutContext();

        $withContext->bindAlias($aliasDefault, Service2Class::class);
        $withContext->bindAlias($aliasContext, Service2Class::class);
        $withContext->bind(Service2Class::class, Service2Class::class);

        self::assertTrue($container->isAlias($aliasDefault));
        // This alias should only exist in the context aware container
        self::assertFalse($container->isAlias($aliasContext));
        self::assertInstanceOf(ServiceClass::class, $container->get($aliasDefault));
        self::assertInstanceOf(ServiceClass::class, $container->getService($aliasDefault));

        // We added both aliases to ensure that the default alias in the context aware container returned Service2
        // and thus works correctly
        self::assertTrue($withContext->isAlias($aliasDefault));
        self::assertTrue($withContext->isAlias($aliasContext));
        self::assertInstanceOf(Service2Class::class, $withContext->get($aliasDefault));
        self::assertInstanceOf(Service2Class::class, $withContext->get($aliasContext));
        self::assertInstanceOf(Service2Class::class, $withContext->getService($aliasDefault));
        self::assertInstanceOf(Service2Class::class, $withContext->getService($aliasContext));

        self::assertTrue($withoutContext->isAlias($aliasDefault));
        self::assertFalse($withoutContext->isAlias($aliasContext));
        self::assertInstanceOf(ServiceClass::class, $withoutContext->get($aliasDefault));
        self::assertInstanceOf(ServiceClass::class, $withoutContext->getService($aliasDefault));
    }

    public function testSingleton(): void
    {
        $container = new ContextAwareContainer();

        $container->bindSingleton(SingletonClass::class, SingletonClass::class);

        $withContext    = $container->withContext(SingletonClass::class, 'make');
        $withoutContext = $container->withoutContext();

        $withContext->bindSingleton(SingletonClass::class, Singleton2Class::class);

        self::assertTrue($container->isSingleton(SingletonClass::class));
        self::assertInstanceOf(SingletonClass::class, $container->get(SingletonClass::class));
        self::assertInstanceOf(SingletonClass::class, $container->getSingleton(SingletonClass::class));

        self::assertTrue($withContext->isSingleton(SingletonClass::class));
        self::assertInstanceOf(Singleton2Class::class, $withContext->get(SingletonClass::class));
        self::assertInstanceOf(Singleton2Class::class, $withContext->getSingleton(SingletonClass::class));

        self::assertTrue($withoutContext->isSingleton(SingletonClass::class));
        self::assertInstanceOf(SingletonClass::class, $withoutContext->get(SingletonClass::class));
        self::assertInstanceOf(SingletonClass::class, $withoutContext->getSingleton(SingletonClass::class));
    }

    public function testClosure(): void
    {
        $container = new ContextAwareContainer();

        $id = 'closureTest';

        $valueDefault = new class {
            public string $value = 'foo';
        };

        $valueContext = new class {
            public string $value = 'bar';
        };

        $closureDefault = static fn (): object => $valueDefault;
        $closureContext = static fn (): object => $valueContext;

        $container->setCallable($id, $closureDefault);

        $withContext    = $container->withContext('closure');
        $withoutContext = $container->withoutContext();

        $withContext->setCallable($id, $closureContext);

        self::assertTrue($container->isCallable($id));
        self::assertSame($valueDefault, $container->get($id));
        self::assertSame($valueDefault, $container->getCallable($id));

        self::assertTrue($withContext->isCallable($id));
        self::assertSame($valueContext, $withContext->get($id));
        self::assertSame($valueContext, $withContext->getCallable($id));

        self::assertTrue($withoutContext->isCallable($id));
        self::assertSame($valueDefault, $withoutContext->get($id));
        self::assertSame($valueDefault, $withoutContext->getCallable($id));
    }
}
