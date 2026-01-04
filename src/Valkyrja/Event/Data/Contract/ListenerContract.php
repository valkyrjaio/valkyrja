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

use Valkyrja\Dispatch\Data\Contract\ClassDispatchContract;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;

interface ListenerContract
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
     */
    public function withName(string $name): static;

    /**
     * Get the dispatch.
     */
    public function getDispatch(): ClassDispatchContract|MethodDispatchContract;

    /**
     * Create new listener with the specified dispatch.
     *
     * @param ClassDispatchContract|MethodDispatchContract $dispatch The dispatch
     */
    public function withDispatch(ClassDispatchContract|MethodDispatchContract $dispatch): static;
}
