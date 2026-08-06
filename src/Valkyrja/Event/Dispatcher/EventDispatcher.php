<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Event\Dispatcher;

use Override;
use Psr\EventDispatcher\StoppableEventInterface;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidReferenceException;
use Valkyrja\Event\Collection\Contract\ListenerCollectionContract;
use Valkyrja\Event\Collection\ListenerCollection;
use Valkyrja\Event\Contract\ArgumentsCapableEventContract;
use Valkyrja\Event\Contract\DispatchCollectableEventContract;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract;
use Valkyrja\Event\Throwable\Exception\EventInvalidEventException;

class EventDispatcher implements EventDispatcherContract
{
    public function __construct(
        protected ListenerCollectionContract $collection = new ListenerCollection(),
        protected ContainerContract $container = new Container(),
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatch(object $event): object
    {
        // Get all the listeners for the event
        $listeners = $this->collection->getListenersForEvent($event);

        return $this->dispatchListeners($event, ...$listeners);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatchIfHasListeners(object $event): object
    {
        if ($this->collection->hasListenersForEvent($event)) {
            return $this->dispatch($event);
        }

        return $event;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatchById(string $eventId, array $arguments = []): object
    {
        return $this->dispatch(
            $this->getEventFromId($eventId, $arguments)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatchByIdIfHasListeners(string $eventId, array $arguments = []): object
    {
        $event = $this->getEventFromId($eventId, $arguments);

        if ($this->collection->hasListenersForEventById($eventId)) {
            return $this->dispatch($event);
        }

        return $event;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatchListeners(object $event, ListenerContract ...$listeners): object
    {
        // Iterate through all the listeners
        foreach ($listeners as $listener) {
            // Dispatch the listener with the event
            $event = $this->dispatchListener($event, $listener);

            // If the event is a stoppable event and is marked to stop propagation by the listener that just ran then stop propagation
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }
        }

        return $event;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatchListener(object $event, ListenerContract $listener): object
    {
        $handler = $listener->getHandler();
        // Dispatch the listener with the event
        /** @var scalar|object|array<array-key, mixed>|resource|null $dispatch */
        $dispatch = $handler($this->container, ['event' => $event]);

        // If the event is a dispatch collectable event
        if ($event instanceof DispatchCollectableEventContract) {
            // Add the dispatch result to the event
            $event->addDispatch($dispatch);
        }

        return $event;
    }

    /**
     * Get an event class from a given id.
     *
     * @param class-string            $eventId   The event class name
     * @param array<array-key, mixed> $arguments The arguments to pass to the event class
     *
     * @throws ContainerInvalidReferenceException When the container resolves nothing for the id
     * @throws EventInvalidEventException         When the container resolves the id to a different type
     */
    protected function getEventFromId(string $eventId, array $arguments = []): object
    {
        $event = $this->container->get($eventId, $arguments);

        if (! $event instanceof $eventId) {
            throw new EventInvalidEventException($eventId);
        }

        if ($event instanceof ArgumentsCapableEventContract) {
            return $event->setArguments($arguments);
        }

        return $event;
    }
}
