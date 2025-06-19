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

namespace Valkyrja\Event\Data\Contract;

use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;

/**
 * Interface Listener.
 *
 * @author Melech Mizrachi
 */
interface Listener
{
    /**
     * Get the event class name.
     *
     * @return class-string
     */
    public function getEventId(): string;

    /**
     * Create a new listener with the specified event class name.
     *
     * @param class-string $eventId The event class name
     *
     * @return static
     */
    public function withEventId(string $eventId): static;

    /**
     * Get the unique name.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Create a new listener with the specified unique name.
     *
     * @param non-empty-string $name A unique name for the listener
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the dispatch.
     *
     * @return ClassDispatch|MethodDispatch
     */
    public function getDispatch(): ClassDispatch|MethodDispatch;

    /**
     * Create new listener with the specified dispatch.
     *
     * @param ClassDispatch|MethodDispatch $dispatch The dispatch
     *
     * @return static
     */
    public function withDispatch(ClassDispatch|MethodDispatch $dispatch): static;
}
