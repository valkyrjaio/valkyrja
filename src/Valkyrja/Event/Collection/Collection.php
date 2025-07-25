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

use Override;
use Valkyrja\Event\Collection\Contract\Collection as Contract;
use Valkyrja\Event\Data;
use Valkyrja\Event\Data\Contract\Listener;
use Valkyrja\Event\Data\Listener as Model;
use Valkyrja\Event\Exception\InvalidArgumentException;

use function array_keys;
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
    #[Override]
    public function getData(): Data
    {
        return new Data(
            events: $this->events,
            listeners: array_map(
                static fn (Listener|string $listener): string => ! is_string($listener)
                    ? serialize($listener)
                    : $listener,
                $this->listeners
            ),
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setFromData(Data $data): void
    {
        $this->events    = $data->events;
        $this->listeners = $data->listeners;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasListener(Listener $listener): bool
    {
        return $this->hasListenerById($listener->getName());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasListenerById(string $listenerId): bool
    {
        return isset($this->listeners[$listenerId]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function removeListener(Listener $listener): void
    {
        $listenerId = $listener->getName();
        $eventId    = $listener->getEventId();

        unset($this->events[$eventId][$listenerId], $this->listeners[$listenerId]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function hasListenersForEvent(object $event): bool
    {
        return isset($this->events[$event::class])
            && $this->events[$event::class] !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasListenersForEventById(string $eventId): bool
    {
        return isset($this->events[$eventId])
            && $this->events[$eventId] !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getListenersForEvent(object $event): array
    {
        return $this->getListenersForEventById($event::class);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getListenersForEventById(string $eventId): array
    {
        $listenerIds = $this->events[$eventId] ?? null;
        $listeners   = [];

        if ($listenerIds === null) {
            return [];
        }

        foreach ($listenerIds as $listenerId) {
            $listener               = $this->listeners[$listenerId];
            $listeners[$listenerId] = $this->ensureListener($listener);
        }

        return $listeners;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setListenersForEvent(object $event, Listener ...$listeners): void
    {
        $this->setListenersForEventById($event::class, ...$listeners);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function removeListenersForEvent(object $event): void
    {
        $this->removeListenersForEventById($event::class);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function removeListenersForEventById(string $eventId): void
    {
        $listeners = $this->getListenersForEventById($eventId);

        foreach ($listeners as $listener) {
            $this->removeListener($listener);
        }

        unset($this->events[$eventId]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
    #[Override]
    public function getEvents(): array
    {
        return array_keys($this->events);
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
