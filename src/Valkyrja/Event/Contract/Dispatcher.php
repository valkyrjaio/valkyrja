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

namespace Valkyrja\Event\Contract;

use Psr\EventDispatcher\EventDispatcherInterface;
use Valkyrja\Event\Model\Contract\Listener;

/**
 * Interface Dispatcher.
 *
 * @author Melech Mizrachi
 */
interface Dispatcher extends EventDispatcherInterface
{
    /**
     * @inheritDoc
     */
    public function dispatch(object $event): object;

    /**
     * Dispatch an event if it has listeners.
     *
     * @param object $event The event class
     */
    public function dispatchIfHasListeners(object $event): object|null;

    /**
     * Dispatch an event by its id.
     *
     * @param class-string            $eventId   The event class name
     * @param array<array-key, mixed> $arguments The arguments to pass to the event class
     */
    public function dispatchById(string $eventId, array $arguments = []): object;

    /**
     * Dispatch an event by its id if it has listeners.
     *
     * @param class-string            $eventId   The event class name
     * @param array<array-key, mixed> $arguments The arguments to pass to the event class
     */
    public function dispatchByIdIfHasListeners(string $eventId, array $arguments = []): object|null;

    /**
     * Dispatch a set of listeners.
     */
    public function dispatchListeners(object $event, Listener ...$listeners): object;

    /**
     * Dispatch a set of listeners given an event id.
     *
     * @param class-string $eventId The event class name
     */
    public function dispatchListenersGivenId(string $eventId, Listener ...$listeners): object;

    /**
     * Dispatch a listener.
     */
    public function dispatchListener(object $event, Listener $listener): object;

    /**
     * Dispatch a listener given an event id.
     *
     * @param class-string $eventId The event class name
     */
    public function dispatchListenerGivenId(string $eventId, Listener $listener): object;
}
