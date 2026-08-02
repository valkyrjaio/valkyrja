<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Event\Dispatcher;

use stdClass;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Event\Collection\ListenerCollection;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Dispatcher\EventDispatcher;
use Valkyrja\Tests\Fixtures\Event\ArgumentsCapableEventFixture;
use Valkyrja\Tests\Fixtures\Event\DispatchCollectableEventFixture;
use Valkyrja\Tests\Fixtures\Event\StoppableEventFixture;
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
    public static function dispatchCallback(DispatchCollectableEventFixture|StoppableEventFixture $event): string
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

        $eventId      = DispatchCollectableEventFixture::class;
        $event        = new DispatchCollectableEventFixture();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn (ContainerContract $container, array $arguments) => self::dispatchCallback($arguments['event'])
        );

        $collection = new ListenerCollection();

        $collection->addListener($listener);

        $dispatcher = new EventDispatcher(collection: $collection);

        /** @var DispatchCollectableEventFixture $eventAfterDispatch */
        $eventAfterDispatch = $dispatcher->dispatch($event);
        /** @var DispatchCollectableEventFixture $eventAfterDispatchById */
        $eventAfterDispatchById = $dispatcher->dispatchById($eventId);

        self::assertTrue(self::$dispatched);
        self::assertSame(['test'], $eventAfterDispatch->getDispatches());
        self::assertSame(['test'], $eventAfterDispatchById->getDispatches());

        self::$dispatched = false;

        $event2 = new DispatchCollectableEventFixture();

        $collection->addListener($listener->withName('listener2'));
        $collection->addListener($listener->withName('listener3'));

        /** @var DispatchCollectableEventFixture $eventAfterDispatch2 */
        $eventAfterDispatch2 = $dispatcher->dispatch($event2);
        /** @var DispatchCollectableEventFixture $eventAfterDispatchById2 */
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

        $eventId      = DispatchCollectableEventFixture::class;
        $event        = new DispatchCollectableEventFixture();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn (ContainerContract $container, array $arguments) => self::dispatchCallback($arguments['event'])
        );

        $collection = new ListenerCollection();

        $dispatcher = new EventDispatcher(collection: $collection);

        $eventAfterDispatch     = $dispatcher->dispatchIfHasListeners($event);
        $eventAfterDispatchById = $dispatcher->dispatchByIdIfHasListeners($eventId);

        self::assertFalse(self::$dispatched);
        self::assertSame($event, $eventAfterDispatch);
        self::assertInstanceOf($eventId, $eventAfterDispatchById);

        $collection->addListener($listener);

        /** @var DispatchCollectableEventFixture $eventAfterDispatch2 */
        $eventAfterDispatch2 = $dispatcher->dispatchIfHasListeners($event);
        /** @var DispatchCollectableEventFixture $eventAfterDispatchById2 */
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

        $eventId      = StoppableEventFixture::class;
        $event        = new StoppableEventFixture();
        $listenerName = 'listener';
        $listener     = new Listener(
            eventId: $eventId,
            name: $listenerName,
            handler: static fn (ContainerContract $container, array $arguments) => self::dispatchCallback($arguments['event'])
        );

        $collection = new ListenerCollection();

        $collection->addListener($listener);
        $collection->addListener($listener->withName('listener2'));
        $collection->addListener($listener->withName('listener3'));

        $dispatcher = new EventDispatcher(collection: $collection);

        /** @var StoppableEventFixture $eventAfterDispatch */
        $eventAfterDispatch = $dispatcher->dispatch($event);
        /** @var StoppableEventFixture $eventAfterDispatchById */
        $eventAfterDispatchById = $dispatcher->dispatchById($eventId);

        self::assertTrue(self::$dispatched);
        // Despite there being 3 listeners, there should only be one dispatch because we have isPropagationStopped() as true
        self::assertSame(['test'], $eventAfterDispatch->getDispatches());
        self::assertSame(['test'], $eventAfterDispatchById->getDispatches());

        self::$dispatched = false;
    }

    /**
     * Test dispatchById with a non-existent class returns stdClass.
     */
    public function testDispatchByIdWithNonExistentClassReturnsStdClass(): void
    {
        $dispatcher = new EventDispatcher();

        $result = $dispatcher->dispatchById('NonExistent\\Class\\Name');

        self::assertInstanceOf(stdClass::class, $result);
    }

    /**
     * Test dispatchById with a class implementing ArgumentsCapableEventContract.
     */
    public function testDispatchByIdWithArgumentsCapableEvent(): void
    {
        $dispatcher = new EventDispatcher();

        $result = $dispatcher->dispatchById(ArgumentsCapableEventFixture::class);

        self::assertInstanceOf(ArgumentsCapableEventFixture::class, $result);
    }
}
