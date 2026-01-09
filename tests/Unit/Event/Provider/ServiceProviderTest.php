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

namespace Valkyrja\Tests\Unit\Event\Provider;

use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Config;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract as DispatchDispatcherContract;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\CollectorContract;
use Valkyrja\Event\Data\Data;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Dispatcher\Dispatcher;
use Valkyrja\Event\Provider\ServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
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
        self::assertArrayHasKey(DispatcherContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(CollectionContract::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(CollectorContract::class, ServiceProvider::provides());
        self::assertContains(DispatcherContract::class, ServiceProvider::provides());
        self::assertContains(CollectionContract::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributesCollector(): void
    {
        $this->container->setSingleton(AttributeCollectorContract::class, self::createStub(AttributeCollectorContract::class));
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = ServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishDispatcher(): void
    {
        $this->container->setSingleton(DispatchDispatcherContract::class, self::createStub(DispatchDispatcherContract::class));
        $this->container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        $callback = ServiceProvider::publishers()[DispatcherContract::class];
        $callback($this->container);

        self::assertInstanceOf(Dispatcher::class, $this->container->getSingleton(DispatcherContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $this->container->setSingleton(Data::class, new Data());

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithConfig(): void
    {
        $this->container->setSingleton(Config::class, new Config());
        $this->container->setSingleton(CollectorContract::class, $collector = self::createStub(CollectorContract::class));

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(eventId: $eventId, name: $listenerName);

        $collector->method('getListeners')->willReturn([$listener]);

        $callback = ServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertContains($eventId, $collection->getEvents());
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenersForEventById($eventId));
    }
}
