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
        $serviceId = Service::class;

        $container->bind($serviceId, $serviceId);

        self::assertTrue($container->has($serviceId));
        self::assertTrue($container->isService($serviceId));

        self::assertFalse($container->isAlias($serviceId));
        self::assertFalse($container->isClosure($serviceId));
        self::assertFalse($container->isSingleton($serviceId));
        self::assertFalse($container->isProvided($serviceId));
        self::assertFalse($container->isPublished($serviceId));

        self::assertInstanceOf($serviceId, $service = $container->get($serviceId));
        // A bound service should return a new instance each time it is retrieved
        self::assertNotSame($service, $container->get($serviceId));

        self::assertInstanceOf($serviceId, $container->getService($serviceId));
        self::assertNotSame($service, $container->getService($serviceId));
    }

    public function testBindAlias(): void
    {
        $container = $this->container;
        $serviceId = Service::class;
        $alias     = 'alias';

        $container->bindAlias($alias, $serviceId);

        self::assertTrue($container->has($alias));
        self::assertTrue($container->isAlias($alias));
        self::assertTrue($container->isService($alias));

        self::assertFalse($container->isClosure($serviceId));
        self::assertFalse($container->isSingleton($serviceId));
        self::assertFalse($container->isProvided($serviceId));
        self::assertFalse($container->isPublished($serviceId));

        self::assertInstanceOf($serviceId, $service = $container->get($alias));
        // A bound service should return a new instance each time it is retrieved
        self::assertNotSame($service, $container->get($alias));

        self::assertInstanceOf($serviceId, $container->getService($alias));
        self::assertNotSame($service, $container->getService($alias));
    }

    public function testBindSingleton(): void
    {
        $container = $this->container;
        $serviceId = Singleton::class;

        $container->bindSingleton($serviceId, $serviceId);

        self::assertTrue($container->has($serviceId));
        self::assertTrue($container->isSingleton($serviceId));
        // A singleton is a service when bound
        self::assertTrue($container->isService($serviceId));

        self::assertFalse($container->isAlias($serviceId));
        self::assertFalse($container->isClosure($serviceId));
        self::assertFalse($container->isProvided($serviceId));
        self::assertFalse($container->isPublished($serviceId));

        self::assertInstanceOf($serviceId, $service = $container->get($serviceId));
        // A bound singleton should return the same instance each time it is retrieved
        self::assertSame($service, $container->get($serviceId));

        self::assertInstanceOf($serviceId, $container->getSingleton($serviceId));
        self::assertSame($service, $container->getSingleton($serviceId));
    }

    public function testOffsetGetSetAndExists(): void
    {
        $container = $this->container;
        $serviceId = Service::class;

        $container[$serviceId] = $serviceId;

        self::assertTrue(isset($container[$serviceId]));
        self::assertInstanceOf($serviceId, $service = $container[$serviceId]);
        // A bound service should return a new instance each time it is gotten
        self::assertNotSame($service, $container[$serviceId]);
    }

    public function testClosure(): void
    {
        $container = $this->container;
        $serviceId = self::class;
        $closure   = static fn () => new self();

        $container->setClosure($serviceId, $closure);

        self::assertTrue($container->has($serviceId));
        self::assertTrue($container->isClosure($serviceId));
        // Set methods will automatically set the service id to published
        // Bounding is a deferment technique, whilst setting is not-deferred and hence should be used through providers
        self::assertTrue($container->isPublished($serviceId));

        self::assertFalse($container->isAlias($serviceId));
        self::assertFalse($container->isSingleton($serviceId));
        self::assertFalse($container->isService($serviceId));
        self::assertFalse($container->isProvided($serviceId));

        self::assertInstanceOf($serviceId, $service = $container->get($serviceId));
        // A bound service should return a new instance each time it is retrieved
        // Of course an application can choose to return the same instance each time, but why not use a singleton then?
        self::assertNotSame($service, $container->get($serviceId));

        self::assertInstanceOf($serviceId, $container->getClosure($serviceId));
        self::assertNotSame($service, $container->getClosure($serviceId));
    }

    public function testOffsetUnset(): void
    {
        $container = $this->container;
        $serviceId = Service::class;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Cannot remove service with name $serviceId from the container.");

        $container[$serviceId] = $serviceId;

        unset($container[$serviceId]);
    }

    public function testProvided(): void
    {
        $container = $this->container;

        self::assertTrue($container->has(Dispatcher::class));
    }
}
