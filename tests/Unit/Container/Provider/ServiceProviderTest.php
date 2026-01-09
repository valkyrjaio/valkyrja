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

namespace Valkyrja\Tests\Unit\Container\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Config;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Attribute\Service;
use Valkyrja\Container\Collector\AttributeCollector;
use Valkyrja\Container\Collector\Contract\CollectorContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Provider\ServiceProvider;
use Valkyrja\Container\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Classes\Container\Singleton2Class;
use Valkyrja\Tests\Classes\Container\SingletonClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(CollectorContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Data::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(CollectorContract::class, ServiceProvider::provides());
        self::assertContains(Data::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishAnnotator(): void
    {
        $this->container->setSingleton(AttributeCollectorContract::class, self::createStub(AttributeCollectorContract::class));

        $callback = ServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishData(): void
    {
        $this->container->setSingleton(Config::class, new Config());
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));

        $service   = new Service(serviceId: ServiceClass::class)->withDispatch(new ClassDispatch(ServiceClass::class));
        $singleton = new Service(serviceId: SingletonClass::class, isSingleton: true)->withDispatch(new ClassDispatch(SingletonClass::class));
        $collector->method('getServices')->willReturn([$service, $singleton]);

        $alias = new Alias(serviceId: Singleton2Class::class)->withDispatch(new ClassDispatch(SingletonClass::class));
        $collector->method('getAliases')->willReturn([$alias]);

        $callback = ServiceProvider::publishers()[Data::class];
        $callback($this->container);

        self::assertInstanceOf(Data::class, $this->container->getSingleton(Data::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishDataInvalidService(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->container->setSingleton(Config::class, new Config());
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));

        $service = new Service(serviceId: self::class)->withDispatch(new ClassDispatch(self::class));
        $collector->method('getServices')->willReturn([$service]);

        $callback = ServiceProvider::publishers()[Data::class];
        $callback($this->container);

        self::assertInstanceOf(Data::class, $this->container->getSingleton(Data::class));
    }
}
