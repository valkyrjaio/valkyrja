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

namespace Valkyrja\Tests\Unit\Event\Collection;

use Valkyrja\Event\Collection\ListenerCollection;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Tests\Classes\Event\EventClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Collection service.
 */
final class ListenerCollectionTest extends TestCase
{
    public function testGetData(): void
    {
        $collection = new ListenerCollection();

        $data = $collection->getData();

        self::assertEmpty($data->listeners);
        self::assertEmpty($data->events);

        $eventId      = EventClass::class;
        $event        = new EventClass();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn () => null
        );

        $collection->addListener($listener);

        $data = $collection->getData();

        self::assertArrayHasKey($listenerName, $data->listeners);
        self::assertSame($listener, $data->listeners[$listenerName]());
        self::assertSame([EventClass::class => [$listenerName => $listenerName]], $data->events);
        self::assertSame([$listenerName => $listener], $collection->getListeners());
        self::assertSame([EventClass::class], $collection->getEvents());
        self::assertSame([EventClass::class => [$listenerName => $listener]], $collection->getEventsWithListeners());
        self::assertSame([$listenerName => $listener], $collection->getListenersForEvent($event));
        self::assertSame([$listenerName => $listener], $collection->getListenersForEventById($eventId));
    }

    public function testFromData(): void
    {
        $eventId       = EventClass::class;
        $event         = new EventClass();
        $listenerName  = 'listener';
        $listenerName2 = 'listener2';
        $listener      = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn () => null
        );
        $listener2     = new Listener(
            eventId: $eventId,
            name: $listenerName2,
            handler: static fn () => null
        );

        $data = new EventData(
            events: [EventClass::class => [$listenerName => $listenerName, $listenerName2 => $listenerName2]],
            listeners: [
                $listenerName  => static fn (): ListenerContract => $listener,
                $listenerName2 => static fn (): ListenerContract => $listener2,
            ]
        );

        $collection = new ListenerCollection();
        $collection->setFromData($data);

        $dataFromCollection = $collection->getData();

        self::assertArrayHasKey($listenerName, $dataFromCollection->listeners);
        self::assertSame($listener, $dataFromCollection->listeners[$listenerName]());
        self::assertArrayHasKey($listenerName2, $dataFromCollection->listeners);
        self::assertSame($listener2, $dataFromCollection->listeners[$listenerName2]());
        self::assertSame($data->events, $dataFromCollection->events);
        self::assertSame([$listenerName => $listener, $listenerName2 => $listener2], $collection->getListeners());
        self::assertSame([EventClass::class], $collection->getEvents());
        self::assertSame([EventClass::class => [$listenerName => $listener, $listenerName2 => $listener2]], $collection->getEventsWithListeners());
        self::assertSame([$listenerName => $listener, $listenerName2 => $listener2], $collection->getListenersForEvent($event));
        self::assertSame([$listenerName => $listener, $listenerName2 => $listener2], $collection->getListenersForEventById($eventId));

        $data2 = new EventData(
            events: [EventClass::class => [$listenerName => $listenerName, $listenerName2 => $listenerName2]],
            listeners: [$listenerName => static fn () => $listener, $listenerName2 => static fn () => $listener2]
        );

        $collection = new ListenerCollection();
        $collection->setFromData($data2);

        $dataFromCollection2 = $collection->getData();

        self::assertSame($data2->listeners, $dataFromCollection2->listeners);
        self::assertSame($data2->events, $dataFromCollection2->events);
        self::assertArrayHasKey($listenerName, $collection->getListeners());
        self::assertArrayHasKey($listenerName2, $collection->getListeners());
        self::assertSame([EventClass::class], $collection->getEvents());
        self::assertArrayHasKey(EventClass::class, $collection->getEventsWithListeners());
        self::assertArrayHasKey($listenerName, $collection->getListenersForEvent($event));
        self::assertArrayHasKey($listenerName, $collection->getListenersForEventById($eventId));
        self::assertArrayHasKey($listenerName2, $collection->getListenersForEvent($event));
        self::assertArrayHasKey($listenerName2, $collection->getListenersForEventById($eventId));

        $data3 = new EventData();

        $collection->setFromData($data3);

        $dataFromCollection3 = $collection->getData();

        self::assertEmpty($dataFromCollection3->listeners);
        self::assertEmpty($dataFromCollection3->events);
        self::assertEmpty($collection->getListeners());
        self::assertEmpty($collection->getEvents());
        self::assertEmpty($collection->getEventsWithListeners());
        self::assertEmpty($collection->getListenersForEvent($event));
        self::assertEmpty($collection->getListenersForEventById($eventId));
    }

    public function testAddAndRemoveListener(): void
    {
        $collection = new ListenerCollection();

        $eventId      = EventClass::class;
        $event        = new EventClass();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn () => null
        );

        self::assertFalse($collection->hasListener($listener));
        self::assertFalse($collection->hasListenerById($listenerName));
        self::assertFalse($collection->hasListenersForEvent($event));
        self::assertFalse($collection->hasListenersForEventById($eventId));

        $collection->addListener($listener);

        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListenersForEvent($event));
        self::assertTrue($collection->hasListenersForEventById($eventId));

        $collection->removeListener($listener);

        self::assertFalse($collection->hasListener($listener));
        self::assertFalse($collection->hasListenerById($listenerName));
        self::assertFalse($collection->hasListenersForEvent($event));
        self::assertFalse($collection->hasListenersForEventById($eventId));

        $collection->addListener($listener);

        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListenersForEvent($event));
        self::assertTrue($collection->hasListenersForEventById($eventId));

        $collection->removeListenerById($listenerName);

        self::assertFalse($collection->hasListener($listener));
        self::assertFalse($collection->hasListenerById($listenerName));
        self::assertFalse($collection->hasListenersForEvent($event));
        self::assertFalse($collection->hasListenersForEventById($eventId));

        $collection->setListenersForEvent($event, $listener);

        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListenersForEvent($event));
        self::assertTrue($collection->hasListenersForEventById($eventId));

        $collection->removeListenersForEvent($event);

        self::assertFalse($collection->hasListener($listener));
        self::assertFalse($collection->hasListenerById($listenerName));
        self::assertFalse($collection->hasListenersForEvent($event));
        self::assertFalse($collection->hasListenersForEventById($eventId));

        $collection->setListenersForEventById($eventId, $listener);

        self::assertTrue($collection->hasListener($listener));
        self::assertTrue($collection->hasListenerById($listenerName));
        self::assertTrue($collection->hasListenersForEvent($event));
        self::assertTrue($collection->hasListenersForEventById($eventId));

        $collection->removeListenersForEventById($eventId);

        self::assertFalse($collection->hasListener($listener));
        self::assertFalse($collection->hasListenerById($listenerName));
        self::assertFalse($collection->hasListenersForEvent($event));
        self::assertFalse($collection->hasListenersForEventById($eventId));
    }
}
