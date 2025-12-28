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
use Valkyrja\Application\Config;
use Valkyrja\Attribute\Collector\Contract\Collector as AttributeCollectorContract;
use Valkyrja\Dispatcher\Contract\Dispatcher as DispatchDispatcher;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\Collection as CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\Collector;
use Valkyrja\Event\Data;
use Valkyrja\Event\Dispatcher\Contract\Dispatcher as Contract;
use Valkyrja\Event\Dispatcher\Dispatcher;
use Valkyrja\Event\Provider\ServiceProvider;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

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
    public function testPublishAttributesCollector(): void
    {
        $this->container->setSingleton(AttributeCollectorContract::class, self::createStub(AttributeCollectorContract::class));
        $this->container->setSingleton(Reflection::class, self::createStub(Reflection::class));

        ServiceProvider::publishAttributesCollector($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(Collector::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishDispatcher(): void
    {
        $this->container->setSingleton(DispatchDispatcher::class, self::createStub(DispatchDispatcher::class));
        $this->container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        ServiceProvider::publishDispatcher($this->container);

        self::assertInstanceOf(Dispatcher::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $this->container->setSingleton(Data::class, new Data());

        ServiceProvider::publishCollection($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithConfig(): void
    {
        $this->container->setSingleton(Config::class, new Config());
        $this->container->setSingleton(Collector::class, $collector = self::createStub(Collector::class));

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Data\Listener(eventId: $eventId, name: $listenerName);

        $collector->method('getListeners')->willReturn([$listener]);

        ServiceProvider::publishCollection($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertContains($eventId, $collection->getEvents());
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenersForEventById($eventId));
    }
}
