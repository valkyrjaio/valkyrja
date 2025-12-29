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
use Valkyrja\Attribute\Collector\Contract\Collector;
use Valkyrja\Container\Attribute\Alias;
use Valkyrja\Container\Attribute\Service;
use Valkyrja\Container\Collector\AttributeCollector;
use Valkyrja\Container\Collector\Contract\Collector as CollectorContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Exception\InvalidArgumentException;
use Valkyrja\Container\Provider\ServiceProvider;
use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Tests\Classes\Container\ServiceClass;
use Valkyrja\Tests\Classes\Container\Singleton2Class;
use Valkyrja\Tests\Classes\Container\SingletonClass;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishAnnotator(): void
    {
        $this->container->setSingleton(Collector::class, self::createStub(Collector::class));

        ServiceProvider::publishAttributesCollector($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishData(): void
    {
        $this->container->setSingleton(Config::class, new Config());
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));

        $service   = (new Service(serviceId: ServiceClass::class))->withDispatch(new ClassDispatch(ServiceClass::class));
        $singleton = (new Service(serviceId: SingletonClass::class, isSingleton: true))->withDispatch(new ClassDispatch(SingletonClass::class));
        $collector->method('getServices')->willReturn([$service, $singleton]);

        $alias = (new Alias(serviceId: Singleton2Class::class))->withDispatch(new ClassDispatch(SingletonClass::class));
        $collector->method('getAliases')->willReturn([$alias]);

        ServiceProvider::publishData($this->container);

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

        $service = (new Service(serviceId: self::class))->withDispatch(new ClassDispatch(self::class));
        $collector->method('getServices')->willReturn([$service]);

        ServiceProvider::publishData($this->container);

        self::assertInstanceOf(Data::class, $this->container->getSingleton(Data::class));
    }
}
