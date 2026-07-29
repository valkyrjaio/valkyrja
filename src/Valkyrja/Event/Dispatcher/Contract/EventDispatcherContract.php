<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Event\Dispatcher\Contract;

use Override;
use Psr\EventDispatcher\EventDispatcherInterface;
use Valkyrja\Container\Throwable\Exception\ContainerInvalidReferenceException;
use Valkyrja\Event\Data\Contract\ListenerContract;
use Valkyrja\Event\Throwable\Exception\EventInvalidEventException;

interface EventDispatcherContract extends EventDispatcherInterface
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatch(object $event): object;

    /**
     * Dispatch an event if it has listeners.
     *
     * @param object $event The event class
     */
    public function dispatchIfHasListeners(object $event): object;

    /**
     * Dispatch an event by its id.
     *
     * @param string                  $eventId   The event class name, which need not resolve to a loaded class
     * @param array<array-key, mixed> $arguments The arguments to pass to the event class
     *
     * @throws ContainerInvalidReferenceException When the container resolves nothing for the id
     * @throws EventInvalidEventException         When the container resolves the id to a different type
     */
    public function dispatchById(string $eventId, array $arguments = []): object;

    /**
     * Dispatch an event by its id if it has listeners.
     *
     * @param string                  $eventId   The event class name, which need not resolve to a loaded class
     * @param array<array-key, mixed> $arguments The arguments to pass to the event class
     *
     * @throws ContainerInvalidReferenceException When the container resolves nothing for the id
     * @throws EventInvalidEventException         When the container resolves the id to a different type
     */
    public function dispatchByIdIfHasListeners(string $eventId, array $arguments = []): object;

    /**
     * Dispatch a set of listeners.
     */
    public function dispatchListeners(object $event, ListenerContract ...$listeners): object;

    /**
     * Dispatch a listener.
     */
    public function dispatchListener(object $event, ListenerContract $listener): object;
}
