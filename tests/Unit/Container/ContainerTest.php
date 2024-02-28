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
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Valkyrja\Container\Config\Container as Config;
use Valkyrja\Container\Managers\Container;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Tests\Classes\Container\Service;
use Valkyrja\Tests\Classes\Container\Singleton;
use Valkyrja\Tests\Traits\ExpectErrorTrait;

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

        $this->container = new Container(new Config(setup: true));
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
        $id = Service::class;

        $container->bind($id, $id);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isService($id));

        self::assertFalse($container->isAlias($id));
        self::assertFalse($container->isClosure($id));
        self::assertFalse($container->isSingleton($id));
        self::assertFalse($container->isProvided($id));
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
        $id = Service::class;
        $alias     = 'alias';

        $container->bindAlias($alias, $id);

        self::assertTrue($container->has($alias));
        self::assertTrue($container->isAlias($alias));
        self::assertTrue($container->isService($alias));

        self::assertFalse($container->isClosure($id));
        self::assertFalse($container->isSingleton($id));
        self::assertFalse($container->isProvided($id));
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
        $id = Singleton::class;

        $container->bindSingleton($id, $id);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isSingleton($id));
        // A singleton is a service when bound
        self::assertTrue($container->isService($id));

        self::assertFalse($container->isAlias($id));
        self::assertFalse($container->isClosure($id));
        self::assertFalse($container->isProvided($id));
        self::assertFalse($container->isPublished($id));

        self::assertInstanceOf($id, $service = $container->get($id));
        // A bound singleton should return the same instance each time it is retrieved
        self::assertSame($service, $container->get($id));

        self::assertInstanceOf($id, $container->getSingleton($id));
        self::assertSame($service, $container->getSingleton($id));
    }

    public function testOffsetGetSetAndExists(): void
    {
        $container = $this->container;
        $id = Service::class;

        $container[$id] = $id;

        self::assertTrue(isset($container[$id]));
        self::assertInstanceOf($id, $service = $container[$id]);
        // A bound service should return a new instance each time it is gotten
        self::assertNotSame($service, $container[$id]);
    }

    public function testClosure(): void
    {
        $container = $this->container;
        $id = self::class;
        $closure   = static fn () => new self();

        $container->setClosure($id, $closure);

        self::assertTrue($container->has($id));
        self::assertTrue($container->isClosure($id));
        // Set methods will automatically set the service id to published
        // Bounding is a deferment technique, whilst setting is not-deferred and hence should be used through providers
        self::assertTrue($container->isPublished($id));

        self::assertFalse($container->isAlias($id));
        self::assertFalse($container->isSingleton($id));
        self::assertFalse($container->isService($id));
        self::assertFalse($container->isProvided($id));

        self::assertInstanceOf($id, $service = $container->get($id));
        // A bound service should return a new instance each time it is retrieved
        // Of course an application can choose to return the same instance each time, but why not use a singleton then?
        self::assertNotSame($service, $container->get($id));

        self::assertInstanceOf($id, $container->getClosure($id));
        self::assertNotSame($service, $container->getClosure($id));
    }

    public function testOffsetUnset(): void
    {
        $container = $this->container;
        $id = Service::class;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Cannot remove service with name $id from the container.");

        $container[$id] = $id;

        unset($container[$id]);
    }

    public function testProvided(): void
    {
        $container = $this->container;

        self::assertTrue($container->has(Dispatcher::class));
    }
}
