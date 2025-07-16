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

use Override;
use Psr\EventDispatcher\ListenerProviderInterface;
use Valkyrja\Event\Data;
use Valkyrja\Event\Data\Contract\Listener;

/**
 * Interface Dispatcher.
 *
 * @author Melech Mizrachi
 */
interface Collection extends ListenerProviderInterface
{
    /**
     * Get a data representation of the collection.
     */
    public function getData(): Data;

    /**
     * Set data from a data object.
     */
    public function setFromData(Data $data): void;

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
    #[Override]
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
}
