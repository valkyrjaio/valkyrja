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

namespace Valkyrja\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Valkyrja\Dispatcher\Contract\Dispatcher as DispatchDispatcher;
use Valkyrja\Event\Collection\Contract\Collection;
use Valkyrja\Event\Contract\DispatchCollectableEvent;
use Valkyrja\Event\Contract\Dispatcher as Contract;
use Valkyrja\Event\Model\Contract\Listener;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class Dispatcher implements Contract
{
    public function __construct(
        protected Collection $collection,
        protected DispatchDispatcher $dispatcher,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event): object
    {
        // Get all the listeners for the event
        $listeners = $this->collection->getListenersForEvent($event);

        return $this->dispatchListeners($event, ...$listeners);
    }

    /**
     * @inheritDoc
     */
    public function dispatchIfHasListeners(object $event): object|null
    {
        if ($this->collection->hasListenersForEvent($event)) {
            return $this->dispatch($event);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function dispatchById(string $eventId, array $arguments = []): object
    {
        return $this->dispatch(
            $this->getEventClassFromId($eventId)
        );
    }

    /**
     * @inheritDoc
     */
    public function dispatchByIdIfHasListeners(string $eventId, array $arguments = []): object|null
    {
        if ($this->collection->hasListenersForEventById($eventId)) {
            return $this->dispatchById($eventId, $arguments);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function dispatchListeners(object $event, Listener ...$listeners): object
    {
        // Iterate through all the listeners
        foreach ($listeners as $listener) {
            // Dispatch the listener with the event
            $event = $this->dispatchListener($event, $listener);

            // If the listener is a stoppable event and is marked to stop propagation then stop propagation
            if ($listener instanceof StoppableEventInterface && $listener->isPropagationStopped()) {
                return $event;
            }
        }

        return $event;
    }

    /**
     * @inheritDoc
     */
    public function dispatchListenersGivenId(string $eventId, Listener ...$listeners): object
    {
        return $this->dispatchListeners(
            $this->getEventClassFromId($eventId),
            ...$listeners
        );
    }

    /**
     * @inheritDoc
     */
    public function dispatchListener(object $event, Listener $listener): object
    {
        // Dispatch the listener with the event
        $dispatch = $this->dispatcher->dispatch($listener, [$event]);

        // If the event is a dispatch collectable event
        if ($event instanceof DispatchCollectableEvent) {
            // Add the dispatch result to the event
            $event->addDispatch($dispatch);
        }

        return $event;
    }

    /**
     * @inheritDoc
     */
    public function dispatchListenerGivenId(string $eventId, Listener $listener): object
    {
        return $this->dispatchListener(
            $this->getEventClassFromId($eventId),
            $listener
        );
    }

    /**
     * Get an event class from a given id.
     *
     * @param class-string            $eventId   The event class name
     * @param array<array-key, mixed> $arguments The arguments to pass to the event class
     */
    protected function getEventClassFromId(string $eventId, array $arguments = []): object
    {
        /** @psalm-suppress MixedMethodCall The developer should have passed the proper arguments for the class */
        return new $eventId(...$arguments);
    }
}
