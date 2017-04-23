<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Events;

use Valkyrja\Contracts\Application;
use Valkyrja\Events\Listener;

/**
 * Interface Events
 *
 * @package Valkyrja\Contracts\Events
 *
 * @author  Melech Mizrachi
 */
interface Events
{
    /**
     * Events constructor.
     *
     * @param \Valkyrja\Contracts\Application $application The application
     */
    public function __construct(Application $application);

    /**
     * Add an event listener.
     *
     * @param string                    $event    The event
     * @param \Valkyrja\Events\Listener $listener The event listener
     *
     * @return void
     */
    public function addListener(string $event, Listener $listener): void;

    /**
     * Determine whether an event has a specified listener.
     *
     * @param string                    $event    The event
     * @param \Valkyrja\Events\Listener $listener The event listener
     *
     * @return bool
     */
    public function hasListener(string $event, Listener $listener): bool;

    /**
     * Remove an event listener.
     *
     * @param string                    $event    The event
     * @param \Valkyrja\Events\Listener $listener The event listener
     *
     * @return void
     */
    public function removeListener(string $event, Listener $listener): void;

    /**
     * Get the event's listeners.
     *
     * @param string $event The event
     *
     * @return \Valkyrja\Events\Listener[]
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
     * @param array  $arguments The arguments
     *
     * @return array
     */
    public function trigger(string $event, array $arguments = []): array;

    /**
     * Get all events.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Set the events.
     *
     * @param array $events The events
     *
     * @return void
     */
    public function set(array $events): void;
}
