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

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Collection\Contract\ListenerCollectionContract;
use Valkyrja\Event\Collection\ListenerCollection;
use Valkyrja\Event\Collector\AttributeListenerCollector;
use Valkyrja\Event\Collector\Contract\ListenerCollectorContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract;
use Valkyrja\Event\Dispatcher\EventDispatcher;
use Valkyrja\Event\Provider\EventServiceProvider;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Classes\Event\Provider\ListenerProviderClass;

/**
 * Test the ServiceProvider.
 */
#[RunTestsInSeparateProcesses]
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = EventServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ListenerCollectorContract::class, new EventServiceProvider()->publishers());
        self::assertArrayHasKey(EventDispatcherContract::class, new EventServiceProvider()->publishers());
        self::assertArrayHasKey(ListenerCollectionContract::class, new EventServiceProvider()->publishers());
        self::assertArrayHasKey(EventData::class, new EventServiceProvider()->publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributesCollector(): void
    {
        $this->container->setSingleton(CollectorContract::class, self::createStub(CollectorContract::class));

        $callback = new EventServiceProvider()->publishers()[ListenerCollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeListenerCollector::class, $this->container->getSingleton(ListenerCollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributesCollectorWithoutAttributesOrReflector(): void
    {
        $callback = new EventServiceProvider()->publishers()[ListenerCollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeListenerCollector::class, $this->container->getSingleton(ListenerCollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishDispatcher(): void
    {
        $this->container->setSingleton(DispatcherContract::class, self::createStub(DispatcherContract::class));
        $this->container->setSingleton(ListenerCollectionContract::class, self::createStub(ListenerCollectionContract::class));

        $callback = new EventServiceProvider()->publishers()[EventDispatcherContract::class];
        $callback($this->container);

        self::assertInstanceOf(EventDispatcher::class, $this->container->getSingleton(EventDispatcherContract::class));
    }

    public function testPublishCollectionWithCustomDataProvided(): void
    {
        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(EventData::class, new EventData());
        $application->method('getDebugMode')->willReturn(false);

        $callback = new EventServiceProvider()->publishers()[ListenerCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(ListenerCollection::class, $this->container->getSingleton(ListenerCollectionContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $eventId      = self::class;
        $listenerName = 'listener-name';
        $data         = new EventData(
            events: [$eventId => [$listenerName]],
            listeners: [
                $listenerName => new Listener(
                    eventId: $eventId,
                    name: $listenerName,
                    handler: static fn () => null
                ),
            ]
        );

        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(ListenerCollectorContract::class, self::createStub(ListenerCollectorContract::class));
        $this->container->setSingleton(EventData::class, $data);
        $application->method('getDebugMode')->willReturn(false);

        self::assertFalse($this->container->has(ListenerCollectionContract::class));

        $callback = new EventServiceProvider()->publishers()[ListenerCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(ListenerCollection::class, $collection = $this->container->getSingleton(ListenerCollectionContract::class));
        self::assertTrue($this->container->has(EventData::class));
        self::assertTrue($collection->hasListenersForEventById($eventId));
        self::assertTrue($collection->hasListenerById($listenerName));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutData(): void
    {
        $this->container->register(new EventServiceProvider());

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(ListenerCollectorContract::class, $collector = $this->createMock(ListenerCollectorContract::class));
        $application->method('getDebugMode')->willReturn(false);

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn () => null
        );

        $collector->expects($this->once())->method('getListeners')->willReturn([$listener]);

        $application->expects($this->once())->method('getEventProviders')->willReturn([new ListenerProviderClass()]);

        self::assertTrue($this->container->has(EventData::class));

        $callback = new EventServiceProvider()->publishers()[ListenerCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(ListenerCollection::class, $collection = $this->container->getSingleton(ListenerCollectionContract::class));
        self::assertTrue($this->container->has(EventData::class));
        self::assertContains($eventId, $collection->getEvents());
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenersForEventById($eventId));
        self::assertContains(ListenerProviderClass::class, $collection->getEvents());
        self::assertTrue($collection->hasListenerById('listener-from-provider-name'));
        self::assertTrue($collection->hasListenersForEventById(ListenerProviderClass::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutDataDebugModeTrue(): void
    {
        $this->container->register(new EventServiceProvider());

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(ListenerCollectorContract::class, $collector = $this->createMock(ListenerCollectorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn () => null
        );

        $collector->expects($this->once())->method('getListeners')->willReturn([$listener]);

        $application->expects($this->once())->method('getEventProviders')->willReturn([new ListenerProviderClass()]);

        self::assertTrue($this->container->has(EventData::class));

        $callback = new EventServiceProvider()->publishers()[ListenerCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(ListenerCollection::class, $collection = $this->container->getSingleton(ListenerCollectionContract::class));
        self::assertTrue($this->container->has(EventData::class));
        self::assertContains($eventId, $collection->getEvents());
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenersForEventById($eventId));
        self::assertContains(ListenerProviderClass::class, $collection->getEvents());
        self::assertTrue($collection->hasListenerById('listener-from-provider-name'));
        self::assertTrue($collection->hasListenersForEventById(ListenerProviderClass::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutDataNoListeners(): void
    {
        $this->container->register(new EventServiceProvider());

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(ListenerCollectorContract::class, $collector = $this->createMock(ListenerCollectorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn () => null
        );

        $collector->expects($this->never())->method('getListeners')->willReturn([$listener]);

        $application->expects($this->once())->method('getEventProviders')->willReturn([]);

        self::assertTrue($this->container->has(EventData::class));

        $callback = new EventServiceProvider()->publishers()[ListenerCollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(ListenerCollection::class, $collection = $this->container->getSingleton(ListenerCollectionContract::class));
        self::assertTrue($this->container->has(EventData::class));
        self::assertNotContains($eventId, $collection->getEvents());
        self::assertFalse($collection->hasListenerById($listenerName));
        self::assertFalse($collection->hasListener($listener));
        self::assertFalse($collection->hasListenersForEventById($eventId));
        self::assertNotContains(ListenerProviderClass::class, $collection->getEvents());
        self::assertFalse($collection->hasListenerById('listener-from-provider-name'));
        self::assertFalse($collection->hasListenersForEventById(ListenerProviderClass::class));
    }
}
