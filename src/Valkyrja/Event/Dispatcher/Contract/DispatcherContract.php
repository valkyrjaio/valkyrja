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

namespace Valkyrja\Event\Dispatcher\Contract;

use Override;
use Psr\EventDispatcher\EventDispatcherInterface;
use Valkyrja\Event\Data\Contract\ListenerContract;

/**
 * Interface DispatcherContract.
 */
interface DispatcherContract extends EventDispatcherInterface
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
    public function dispatchListeners(object $event, ListenerContract ...$listeners): object;

    /**
     * Dispatch a listener.
     */
    public function dispatchListener(object $event, ListenerContract $listener): object;
}
