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

namespace Valkyrja\Tests\Unit\Event\Dispatcher;

use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Dispatcher\Dispatcher;
use Valkyrja\Tests\Classes\Event\DispatchCollectableEventClass;
use Valkyrja\Tests\Classes\Event\StoppableEventClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Dispatcher service.
 */
final class DispatcherTest extends TestCase
{
    protected static bool $dispatched = false;

    /**
     * Callback test.
     */
    public static function dispatchCallback(DispatchCollectableEventClass|StoppableEventClass $event): string
    {
        self::$dispatched = true;

        return 'test';
    }

    /**
     * Test the dispatch method.
     */
    public function testDispatch(): void
    {
        self::$dispatched = false;

        $eventId      = DispatchCollectableEventClass::class;
        $event        = new DispatchCollectableEventClass();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatchCallback'])
        );

        $collection = new Collection();

        $collection->addListener($listener);

        $dispatcher = new Dispatcher(collection: $collection);

        /** @var DispatchCollectableEventClass $eventAfterDispatch */
        $eventAfterDispatch = $dispatcher->dispatch($event);
        /** @var DispatchCollectableEventClass $eventAfterDispatchById */
        $eventAfterDispatchById = $dispatcher->dispatchById($eventId);

        self::assertTrue(self::$dispatched);
        self::assertSame(['test'], $eventAfterDispatch->getDispatches());
        self::assertSame(['test'], $eventAfterDispatchById->getDispatches());

        self::$dispatched = false;

        $event2 = new DispatchCollectableEventClass();

        $collection->addListener($listener->withName('listener2'));
        $collection->addListener($listener->withName('listener3'));

        /** @var DispatchCollectableEventClass $eventAfterDispatch2 */
        $eventAfterDispatch2 = $dispatcher->dispatch($event2);
        /** @var DispatchCollectableEventClass $eventAfterDispatchById2 */
        $eventAfterDispatchById2 = $dispatcher->dispatchById($eventId);

        self::assertTrue(self::$dispatched);
        self::assertSame(['test', 'test', 'test'], $eventAfterDispatch2->getDispatches());
        self::assertSame(['test', 'test', 'test'], $eventAfterDispatchById2->getDispatches());

        self::$dispatched = false;
    }

    /**
     * Test the dispatch method.
     */
    public function testDispatchIfHasListeners(): void
    {
        self::$dispatched = false;

        $eventId      = DispatchCollectableEventClass::class;
        $event        = new DispatchCollectableEventClass();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatchCallback'])
        );

        $collection = new Collection();

        $dispatcher = new Dispatcher(collection: $collection);

        $eventAfterDispatch     = $dispatcher->dispatchIfHasListeners($event);
        $eventAfterDispatchById = $dispatcher->dispatchByIdIfHasListeners($eventId);

        self::assertFalse(self::$dispatched);
        self::assertNull($eventAfterDispatch);
        self::assertNull($eventAfterDispatchById);

        $collection->addListener($listener);

        /** @var DispatchCollectableEventClass $eventAfterDispatch2 */
        $eventAfterDispatch2 = $dispatcher->dispatchIfHasListeners($event);
        /** @var DispatchCollectableEventClass $eventAfterDispatchById2 */
        $eventAfterDispatchById2 = $dispatcher->dispatchByIdIfHasListeners($eventId);

        self::assertTrue(self::$dispatched);
        self::assertSame(['test'], $eventAfterDispatch2->getDispatches());
        self::assertSame(['test'], $eventAfterDispatchById2->getDispatches());

        self::$dispatched = false;
    }

    /**
     * Test the dispatch method.
     */
    public function testStoppableEventDispatch(): void
    {
        self::$dispatched = false;

        $eventId      = StoppableEventClass::class;
        $event        = new StoppableEventClass();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            dispatch: MethodDispatch::fromCallableOrArray([self::class, 'dispatchCallback'])
        );

        $collection = new Collection();

        $collection->addListener($listener);
        $collection->addListener($listener->withName('listener2'));
        $collection->addListener($listener->withName('listener3'));

        $dispatcher = new Dispatcher(collection: $collection);

        /** @var StoppableEventClass $eventAfterDispatch */
        $eventAfterDispatch = $dispatcher->dispatch($event);
        /** @var StoppableEventClass $eventAfterDispatchById */
        $eventAfterDispatchById = $dispatcher->dispatchById($eventId);

        self::assertTrue(self::$dispatched);
        // Despite there being 3 listeners, there should only be one dispatch because we have isPropagationStopped() as true
        self::assertSame(['test'], $eventAfterDispatch->getDispatches());
        self::assertSame(['test'], $eventAfterDispatchById->getDispatches());

        self::$dispatched = false;
    }
}
