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

use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidReferenceException;
use Valkyrja\Event\Collection\ListenerCollection;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Dispatcher\EventDispatcher;
use Valkyrja\Event\Throwable\Exception\InvalidEventException;
use Valkyrja\Tests\Fixtures\Event\ArgumentsCapableEventFixture;
use Valkyrja\Tests\Fixtures\Event\DispatchCollectableEventFixture;
use Valkyrja\Tests\Fixtures\Event\EventFixture;
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
     * Test dispatchById returns the event that the container is bound to.
     */
    public function testDispatchByIdResolvesTheEventFromTheContainer(): void
    {
        $event = new EventFixture();

        $container = new Container();
        $container->bind(
            EventFixture::class,
            static fn (ContainerContract $container, array $arguments): EventFixture => $event
        );

        $dispatcher = new EventDispatcher(container: $container);

        self::assertSame($event, $dispatcher->dispatchById(EventFixture::class));
    }

    /**
     * Test dispatchById gives the arguments to the container.
     */
    public function testDispatchByIdGivesTheArgumentsToTheContainer(): void
    {
        $argumentsFromContainer = [];

        $container = new Container();
        $container->bind(
            EventFixture::class,
            static function (ContainerContract $container, array $arguments) use (&$argumentsFromContainer): EventFixture {
                $argumentsFromContainer = $arguments;

                return new EventFixture();
            }
        );

        $dispatcher = new EventDispatcher(container: $container);

        $dispatcher->dispatchById(EventFixture::class, ['test']);

        self::assertSame(['test'], $argumentsFromContainer);
    }

    /**
     * Test dispatchById builds the event where the container is bound to nothing.
     */
    public function testDispatchByIdWithUnboundIdBuildsTheEvent(): void
    {
        $dispatcher = new EventDispatcher();

        self::assertInstanceOf(EventFixture::class, $dispatcher->dispatchById(EventFixture::class));
    }

    /**
     * Test dispatchById throws where the container resolves nothing for the id.
     */
    public function testDispatchByIdWithNonExistentClassThrows(): void
    {
        $dispatcher = new EventDispatcher();

        $this->expectException(ContainerInvalidReferenceException::class);

        $dispatcher->dispatchById('NonExistent\\Class\\Name');
    }

    /**
     * Test dispatchById throws where the container resolves the id to a different type.
     */
    public function testDispatchByIdWithNonEventThrows(): void
    {
        $container = new Container();
        $container->bindAlias(EventFixture::class, DispatchCollectableEventFixture::class);

        $dispatcher = new EventDispatcher(container: $container);

        $this->expectException(InvalidEventException::class);
        $this->expectExceptionMessage('Service with `' . EventFixture::class . '` is not an event');

        $dispatcher->dispatchById(EventFixture::class);
    }

    /**
     * Test dispatchById with a class implementing ArgumentsCapableEventContract.
     */
    public function testDispatchByIdWithArgumentsCapableEvent(): void
    {
        $dispatcher = new EventDispatcher();

        /** @var ArgumentsCapableEventFixture $result */
        $result = $dispatcher->dispatchById(ArgumentsCapableEventFixture::class, ['test']);

        self::assertInstanceOf(ArgumentsCapableEventFixture::class, $result);
        self::assertSame(['test'], $result->getArguments());
    }

    /**
     * Test dispatchByIdIfHasListeners throws where the container resolves nothing for the id.
     */
    public function testDispatchByIdIfHasListenersWithNonExistentClassThrows(): void
    {
        $dispatcher = new EventDispatcher();

        $this->expectException(ContainerInvalidReferenceException::class);

        $dispatcher->dispatchByIdIfHasListeners('NonExistent\\Class\\Name');
    }
}
