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

namespace Valkyrja\Event\Collection\Contract;

use ArrayAccess;
use Psr\EventDispatcher\ListenerProviderInterface;
use Valkyrja\Event\Model\Contract\Listener;

/**
 * Interface Dispatcher.
 *
 * @author Melech Mizrachi
 *
 * @extends ArrayAccess<string|object, Listener[]|Listener>
 */
interface Collection extends ArrayAccess, ListenerProviderInterface
{
    /**
     * Determine if a listener is registered.
     */
    public function hasListener(Listener $listener): bool;

    /**
     * Determine if a listener is registered by its id.
     */
    public function hasListenerById(string $listenerId): bool;

    /**
     * Add a listener.
     */
    public function addListener(Listener $listener): void;

    /**
     * Remove a listener.
     */
    public function removeListener(Listener $listener): void;

    /**
     * Remove a listener by id.
     */
    public function removeListenerById(string $listenerId): void;

    /**
     * Determine if listeners exist for a given event.
     */
    public function hasListenersForEvent(object $event): bool;

    /**
     * Determine if listeners exist for a given event id.
     *
     * @param class-string $eventId The event class name
     */
    public function hasListenersForEventById(string $eventId): bool;

    /**
     * @inheritDoc
     *
     * @return Listener[]
     */
    public function getListenersForEvent(object $event): array;

    /**
     * Get all listeners for a given event id.
     *
     * @param class-string $eventId The event class name
     *
     * @return Listener[]
     */
    public function getListenersForEventById(string $eventId): array;

    /**
     * Set listeners for a given event.
     */
    public function setListenersForEvent(object $event, Listener ...$listeners): void;

    /**
     * Set listeners for a given event id.
     *
     * @param class-string $eventId The event class name
     */
    public function setListenersForEventById(string $eventId, Listener ...$listeners): void;

    /**
     * Remove all listeners for a given event.
     */
    public function removeListenersForEvent(object $event): void;

    /**
     * Remove all listeners for a given event id.
     *
     * @param class-string $eventId The event class name
     */
    public function removeListenersForEventById(string $eventId): void;

    /**
     * Get all listeners.
     *
     * @return Listener[]
     */
    public function getListeners(): array;

    /**
     * Get all registered event ids with listeners.
     *
     * @return class-string[]
     */
    public function getEvents(): array;

    /**
     * Get all registered events with their listeners.
     *
     * @return array<class-string, Listener[]>
     */
    public function getEventsWithListeners(): array;

    /**
     * Get an event's listeners.
     *
     * @param class-string|object $offset The event id
     *
     * @return Listener[]
     */
    public function offsetGet(mixed $offset): array;

    /**
     * Set a listener to an event.
     *
     * @param class-string|object $offset The event id
     * @param Listener|Listener[] $value  The listener
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void;

    /**
     * Remove all listeners for an event.
     *
     * @param class-string|object $offset The event id
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void;

    /**
     * Check whether an event has listeners.
     *
     * @param class-string|object $offset The event id
     *
     * @return bool
     */
    public function offsetExists(mixed $offset): bool;
}
