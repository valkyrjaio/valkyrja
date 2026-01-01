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

namespace Valkyrja\Event\Dispatcher;

use Override;
use Psr\EventDispatcher\StoppableEventInterface;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract as DispatchDispatcherContract;
use Valkyrja\Dispatch\Dispatcher\Dispatcher as DispatchDispatcher;
use Valkyrja\Event\Collection\Collection;
use Valkyrja\Event\Collection\Contract\CollectionContract;
use Valkyrja\Event\Contract\DispatchCollectableEventContract;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Event\Dispatcher\Contract\DispatcherContract as Contract;

class Dispatcher implements Contract
{
    public function __construct(
        protected CollectionContract $collection = new Collection(),
        protected DispatchDispatcherContract $dispatcher = new DispatchDispatcher(),
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
    #[Override]
    public function dispatchById(string $eventId, array $arguments = []): object
    {
        return $this->dispatch(
            $this->getEventClassFromId($eventId)
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
        // Dispatch the listener with the event
        /** @var mixed $dispatch */
        $dispatch = $this->dispatcher->dispatch($listener->getDispatch(), ['event' => $event]);

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
     */
    protected function getEventClassFromId(string $eventId, array $arguments = []): object
    {
        /** @psalm-suppress MixedMethodCall The developer should have passed the proper arguments for the class */
        return new $eventId(...$arguments);
    }
}
