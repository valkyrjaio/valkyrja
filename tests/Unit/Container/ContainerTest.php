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

use AssertionError;
use Valkyrja\Application\Contract\Application;
use Valkyrja\Container\Container;
use Valkyrja\Container\Exception\InvalidArgumentException;
use Valkyrja\Dispatcher\Contract\Dispatcher;
use Valkyrja\Dispatcher\Provider\ServiceProvider;
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Classes\Container\SingletonClass;
use Valkyrja\Tests\Trait\ExpectErrorTrait;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the container service.
 *
 * @author Melech Mizrachi
 */
class ContainerTest extends TestCase
{
    use ExpectErrorTrait;

    /**
     * The class to test with.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
    }

    public function testInvalidBind(): void
    {
        $this->expectException(AssertionError::class);
        $this->expectExceptionMessage('assert(is_a($service, Service::class, true))');

        $container = $this->container;

        $container->bind(self::class, self::class);
    }

    public function testBind(): void
    {
        $container = $this->container;
        $id        = ServiceClass::class;

        $container->bind($id, $id);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isService($id));

        self::assertFalse($container->isAlias($id));
        self::assertFalse($container->isCallable($id));
        self::assertFalse($container->isSingleton($id));
        self::assertFalse($container->isDeferred($id));
        self::assertFalse($container->isPublished($id));

        self::assertInstanceOf($id, $service = $container->get($id));
        // A bound service should return a new instance each time it is retrieved
        self::assertNotSame($service, $container->get($id));

        self::assertInstanceOf($id, $container->getService($id));
        self::assertNotSame($service, $container->getService($id));
    }

    public function testBindAlias(): void
    {
        $container = $this->container;
        $id        = ServiceClass::class;
        $alias     = 'alias';

        $container->bind($id, $id);
        $container->bindAlias($alias, $id);

        self::assertTrue($container->has($alias));
        self::assertTrue($container->isAlias($alias));
        self::assertTrue($container->isService($alias));

        self::assertFalse($container->isCallable($id));
        self::assertFalse($container->isSingleton($id));
        self::assertFalse($container->isDeferred($id));
        self::assertFalse($container->isPublished($id));

        self::assertInstanceOf($id, $service = $container->get($alias));
        // A bound service should return a new instance each time it is retrieved
        self::assertNotSame($service, $container->get($alias));

        self::assertInstanceOf($id, $container->getService($alias));
        self::assertNotSame($service, $container->getService($alias));
    }

    public function testBindSingleton(): void
    {
        $container = $this->container;
        $id        = SingletonClass::class;

        $container->bindSingleton($id, $id);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isSingleton($id));
        // A singleton is a service when bound
        self::assertTrue($container->isService($id));

        self::assertFalse($container->isAlias($id));
        self::assertFalse($container->isCallable($id));
        self::assertFalse($container->isDeferred($id));
        self::assertFalse($container->isPublished($id));

        self::assertInstanceOf($id, $service = $container->get($id));
        // A bound singleton should return the same instance each time it is retrieved
        self::assertSame($service, $container->get($id));

        self::assertInstanceOf($id, $container->getSingleton($id));
        self::assertSame($service, $container->getSingleton($id));
    }

    public function testClosure(): void
    {
        $container = $this->container;
        $id        = self::class;
        $closure   = static fn () => new self('test');

        $container->setCallable($id, $closure);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isCallable($id));
        // Set methods will automatically set the service id to published
        // Bounding is a deferment technique, whilst setting is not-deferred and hence should be used through providers
        self::assertTrue($container->isPublished($id));

        self::assertFalse($container->isAlias($id));
        self::assertFalse($container->isSingleton($id));
        self::assertFalse($container->isService($id));
        self::assertFalse($container->isDeferred($id));

        self::assertInstanceOf($id, $service = $container->get($id));
        // A bound service should return a new instance each time it is retrieved
        // Of course an application can choose to return the same instance each time, but why not use a singleton then?
        self::assertNotSame($service, $container->get($id));

        self::assertInstanceOf($id, $container->getCallable($id));
        self::assertNotSame($service, $container->getCallable($id));
    }

    public function testProvided(): void
    {
        $container = $this->container;

        $container->register(ServiceProvider::class);

        self::assertTrue($container->has(Dispatcher::class));
    }

    public function testGetNonExistent(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->container->get(Application::class);
    }

    public function testGetNonExistentSingleton(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->container->getSingleton(self::class);
    }

    public function testGetData(): void
    {
        $container = $this->container;

        $container->register(ServiceProvider::class);

        self::assertTrue($container->has(Dispatcher::class));

        $data = $this->container->getData();

        self::assertSame(
            [
                Dispatcher::class => [ServiceProvider::class, 'publishDispatcher'],
            ],
            $data->deferredCallback
        );

        self::assertSame(
            [
                Dispatcher::class => ServiceProvider::class,
            ],
            $data->deferred
        );

        self::assertEmpty($data->aliases);
        self::assertEmpty($data->providers);
        self::assertEmpty($data->services);
        self::assertEmpty($data->singletons);
    }
}
