<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Event;

use Valkyrja\Support\Cacheable;

/**
 * Interface Events.
 *
 * @author Melech Mizrachi
 */
interface Events extends Cacheable
{
    /**
     * Add an event listener.
     *
     * @param string   $event    The event
     * @param Listener $listener The event listener
     *
     * @return void
     */
    public function listen(string $event, Listener $listener): void;

    /**
     * Add a listener to many events.
     *
     * @param Listener $listener  The listener
     * @param string   ...$events The events
     *
     * @return void
     */
    public function listenMany(Listener $listener, string ...$events): void;

    /**
     * Determine whether an event has a specified listener.
     *
     * @param string $event      The event
     * @param string $listenerId The event listener
     *
     * @return bool
     */
    public function hasListener(string $event, string $listenerId): bool;

    /**
     * Remove an event listener.
     *
     * @param string $event      The event
     * @param string $listenerId The event listener
     *
     * @return void
     */
    public function removeListener(string $event, string $listenerId): void;

    /**
     * Get the event's listeners.
     *
     * @param string $event The event
     *
     * @return Listener[]
     */
    public function getListeners(string $event): array;

    /**
     * Determine whether an event has listeners.
     *
     * @param string $event The event
     *
     * @return bool
     */
    public function hasListeners(string $event): bool;

    /**
     * Add a new event.
     *
     * @param string $event The event
     *
     * @return void
     */
    public function add(string $event): void;

    /**
     * Determine whether an event exists.
     *
     * @param string $event The event
     *
     * @return bool
     */
    public function has(string $event): bool;

    /**
     * Remove an event.
     *
     * @param string $event The event
     *
     * @return void
     */
    public function remove(string $event): void;

    /**
     * Trigger an event.
     *
     * @param string $event     The event
     * @param array  $arguments [optional] The arguments
     *
     * @return mixed[]
     */
    public function trigger(string $event, array $arguments = null): array;

    /**
     * Trigger an event.
     *
     * @param Event $event The event
     *
     * @return mixed[]
     */
    public function event(Event $event): array;

    /**
     * Get all events.
     *
     * @return Listener[][]
     */
    public function all(): array;

    /**
     * Set the events.
     *
     * @param Listener[][] $events The events
     *
     * @return void
     */
    public function setEvents(array $events): void;
}
