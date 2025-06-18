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

namespace Valkyrja\Event\Collection;

use Valkyrja\Event\Collection\Contract\Collection as Contract;
use Valkyrja\Event\Data\Contract\Listener;
use Valkyrja\Event\Data\Listener as Model;
use Valkyrja\Event\Exception\InvalidArgumentException;

use function array_keys;
use function is_array;
use function is_object;
use function is_string;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 */
class Collection implements Contract
{
    /**
     * The events.
     *
     * @var array<class-string, string[]>
     */
    protected array $events = [];

    /**
     * The listeners.
     *
     * @var array<string, Listener|string>
     */
    protected array $listeners = [];

    /**
     * @inheritDoc
     */
    public function hasListener(Listener $listener): bool
    {
        return $this->hasListenerById($listener->getName());
    }

    /**
     * @inheritDoc
     */
    public function hasListenerById(string $listenerId): bool
    {
        return isset($this->listeners[$listenerId]);
    }

    /**
     * @inheritDoc
     */
    public function addListener(Listener $listener): void
    {
        $listenerId = $listener->getName();
        $eventId    = $listener->getEventId();

        $this->events[$eventId] ??= [];
        $this->events[$eventId][$listenerId] = $listenerId;
        $this->listeners[$listenerId]        = $listener;
    }

    /**
     * @inheritDoc
     */
    public function removeListener(Listener $listener): void
    {
        $listenerId = $listener->getName();
        $eventId    = $listener->getEventId();

        unset($this->events[$eventId][$listenerId], $this->listeners[$listenerId]);
    }

    /**
     * @inheritDoc
     */
    public function removeListenerById(string $listenerId): void
    {
        foreach ($this->events as $eventId => $listeners) {
            unset($this->events[$eventId][$listenerId]);
        }

        unset($this->listeners[$listenerId]);
    }

    /**
     * @inheritDoc
     */
    public function hasListenersForEvent(object $event): bool
    {
        return isset($this->events[$event::class]);
    }

    /**
     * @inheritDoc
     */
    public function hasListenersForEventById(string $eventId): bool
    {
        return isset($this->events[$eventId]);
    }

    /**
     * @inheritDoc
     */
    public function getListenersForEvent(object $event): array
    {
        return $this->getListenersForEventById($event::class);
    }

    /**
     * @inheritDoc
     */
    public function getListenersForEventById(string $eventId): array
    {
        $listenerIds = $this->events[$eventId];
        $listeners   = [];

        foreach ($listenerIds as $listenerId) {
            $listener               = $this->listeners[$listenerId];
            $listeners[$listenerId] = $this->ensureListener($listener);
        }

        return $listeners;
    }

    /**
     * @inheritDoc
     */
    public function setListenersForEvent(object $event, Listener ...$listeners): void
    {
        $this->setListenersForEventById($event::class, ...$listeners);
    }

    /**
     * @inheritDoc
     */
    public function setListenersForEventById(string $eventId, Listener ...$listeners): void
    {
        foreach ($listeners as $listener) {
            $this->addListener(
                $listener->withEventId($eventId)
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function removeListenersForEvent(object $event): void
    {
        $this->removeListenersForEventById($event::class);
    }

    /**
     * @inheritDoc
     */
    public function removeListenersForEventById(string $eventId): void
    {
        unset($this->events[$eventId]);
    }

    /**
     * @inheritDoc
     */
    public function getListeners(): array
    {
        return array_map(
            [$this, 'ensureListener'],
            $this->listeners
        );
    }

    /**
     * @inheritDoc
     */
    public function getEvents(): array
    {
        return array_keys($this->events);
    }

    /**
     * @inheritDoc
     */
    public function getEventsWithListeners(): array
    {
        $eventsWithListeners = [];
        $events              = $this->events;

        foreach ($events as $eventId => $listenerIds) {
            $eventsWithListeners[$eventId] = $this->getListenersForEventById($eventId);
        }

        return $eventsWithListeners;
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): array
    {
        if (is_object($offset)) {
            return $this->getListenersForEvent($offset);
        }

        return $this->getListenersForEventById($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $listeners = is_array($value) ? $value : [$value];

        if (is_object($offset)) {
            $this->setListenersForEvent($offset, ...$listeners);

            return;
        }

        $this->setListenersForEventById($offset, ...$listeners);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        if (is_object($offset)) {
            $this->removeListenersForEvent($offset);

            return;
        }

        $this->removeListenersForEventById($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        if (is_object($offset)) {
            return $this->hasListenersForEvent($offset);
        }

        return $this->hasListenersForEventById($offset);
    }

    /**
     * Ensure a listener, or null, is returned.
     *
     * @param Listener|string $listener The listener
     *
     * @return Listener
     */
    protected function ensureListener(Listener|string $listener): Listener
    {
        if (is_string($listener)) {
            $unserializedListener = unserialize($listener, ['allowed_classes' => true]);

            if (! $unserializedListener instanceof Model) {
                throw new InvalidArgumentException('Invalid object serialized.');
            }

            return $unserializedListener;
        }

        return $listener;
    }
}
