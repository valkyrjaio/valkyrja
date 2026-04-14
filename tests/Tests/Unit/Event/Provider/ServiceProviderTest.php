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
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeCollectorContract;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\CollectionContract;
use Valkyrja\Event\Collector\AttributeCollector;
use Valkyrja\Event\Collector\Contract\CollectorContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract;
use Valkyrja\Event\Dispatcher\EventDispatcher;
use Valkyrja\Event\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Event\Generator\DataFileGenerator;
use Valkyrja\Event\Provider\EventServiceProvider;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Support\Generator\Enum\GenerateStatus;
use Valkyrja\Tests\Classes\Event\Provider\ListenerProviderClass;
use Valkyrja\Tests\Unit\Container\Provider\Abstract\ServiceProviderTestCase;

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
        self::assertArrayHasKey(CollectorContract::class, EventServiceProvider::publishers());
        self::assertArrayHasKey(EventDispatcherContract::class, EventServiceProvider::publishers());
        self::assertArrayHasKey(CollectionContract::class, EventServiceProvider::publishers());
        self::assertArrayHasKey(DataFileGeneratorContract::class, EventServiceProvider::publishers());
        self::assertArrayHasKey(EventData::class, EventServiceProvider::publishers());
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributesCollector(): void
    {
        $this->container->setSingleton(AttributeCollectorContract::class, self::createStub(AttributeCollectorContract::class));
        $this->container->setSingleton(ReflectorContract::class, self::createStub(ReflectorContract::class));

        $callback = EventServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishAttributesCollectorWithoutAttributesOrReflector(): void
    {
        $callback = EventServiceProvider::publishers()[CollectorContract::class];
        $callback($this->container);

        self::assertInstanceOf(AttributeCollector::class, $this->container->getSingleton(CollectorContract::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishDispatcher(): void
    {
        $this->container->setSingleton(DispatcherContract::class, self::createStub(DispatcherContract::class));
        $this->container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        $callback = EventServiceProvider::publishers()[EventDispatcherContract::class];
        $callback($this->container);

        self::assertInstanceOf(EventDispatcher::class, $this->container->getSingleton(EventDispatcherContract::class));
    }

    public function testPublishCollectionWithCustomDataProvided(): void
    {
        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(EventData::class, new EventData());
        $application->method('getDebugMode')->willReturn(false);

        $callback = EventServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $this->container->getSingleton(CollectionContract::class));
    }

    public function testPublishCollectionWithData(): void
    {
        $eventId      = self::class;
        $listenerName = 'listener-name';
        $data         = new EventData(
            events: [$eventId => [$listenerName]],
            listeners: [$listenerName => new Listener(eventId: $eventId, name: $listenerName)]
        );

        $this->container->setSingleton(ApplicationContract::class, $application = self::createStub(ApplicationContract::class));
        $this->container->setSingleton(CollectorContract::class, self::createStub(CollectorContract::class));
        $this->container->setSingleton(EventData::class, $data);
        $application->method('getDebugMode')->willReturn(false);

        self::assertFalse($this->container->has(CollectionContract::class));

        $callback = EventServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertTrue($this->container->has(EventData::class));
        self::assertTrue($collection->hasListenersForEventById($eventId));
        self::assertTrue($collection->hasListenerById($listenerName));
    }

    /**
     * @throws Exception
     */
    public function testPublishCollectionWithoutData(): void
    {
        $this->container->register(EventServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(CollectorContract::class, $collector = $this->createMock(CollectorContract::class));
        $this->container->setSingleton(DataFileGeneratorContract::class, $generator = $this->createMock(DataFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(false);

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(eventId: $eventId, name: $listenerName);

        $collector->expects($this->once())->method('getListeners')->willReturn([$listener]);
        $generator->expects($this->never())->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->expects($this->once())->method('getEventProviders')->willReturn([ListenerProviderClass::class]);

        self::assertTrue($this->container->has(EventData::class));

        $callback = EventServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
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
        $this->container->register(EventServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(CollectorContract::class, $collector = $this->createMock(CollectorContract::class));
        $this->container->setSingleton(DataFileGeneratorContract::class, $generator = $this->createMock(DataFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(eventId: $eventId, name: $listenerName);

        $collector->expects($this->once())->method('getListeners')->willReturn([$listener]);
        $generator->expects($this->never())->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->expects($this->once())->method('getEventProviders')->willReturn([ListenerProviderClass::class]);

        self::assertTrue($this->container->has(EventData::class));

        $callback = EventServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
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
        $this->container->register(EventServiceProvider::class);

        $this->container->setSingleton(ApplicationContract::class, $application = $this->createMock(ApplicationContract::class));
        $this->container->setSingleton(CollectorContract::class, $collector = $this->createMock(CollectorContract::class));
        $this->container->setSingleton(DataFileGeneratorContract::class, $generator = $this->createMock(DataFileGeneratorContract::class));
        $application->method('getDebugMode')->willReturn(true);

        $eventId      = self::class;
        $listenerName = 'listener-name';
        $listener     = new Listener(eventId: $eventId, name: $listenerName);

        $collector->expects($this->never())->method('getListeners')->willReturn([$listener]);
        $generator->expects($this->never())->method('generateFile')->willReturn(GenerateStatus::SUCCESS);

        $application->expects($this->once())->method('getEventProviders')->willReturn([]);

        self::assertTrue($this->container->has(EventData::class));

        $callback = EventServiceProvider::publishers()[CollectionContract::class];
        $callback($this->container);

        self::assertInstanceOf(Collection::class, $collection = $this->container->getSingleton(CollectionContract::class));
        self::assertTrue($this->container->has(EventData::class));
        self::assertNotContains($eventId, $collection->getEvents());
        self::assertFalse($collection->hasListenerById($listenerName));
        self::assertFalse($collection->hasListener($listener));
        self::assertFalse($collection->hasListenersForEventById($eventId));
        self::assertNotContains(ListenerProviderClass::class, $collection->getEvents());
        self::assertFalse($collection->hasListenerById('listener-from-provider-name'));
        self::assertFalse($collection->hasListenersForEventById(ListenerProviderClass::class));
    }

    public function testPublishDataFileGenerator(): void
    {
        $container = $this->container;

        $container->setSingleton(CollectionContract::class, self::createStub(CollectionContract::class));

        self::assertFalse($container->has(CollectorContract::class));

        $callback = EventServiceProvider::publishers()[DataFileGeneratorContract::class];
        $callback($this->container);

        self::assertTrue($container->has(DataFileGeneratorContract::class));
        self::assertTrue($container->isSingleton(DataFileGeneratorContract::class));
        self::assertInstanceOf(DataFileGenerator::class, $container->getSingleton(DataFileGeneratorContract::class));
    }
}
