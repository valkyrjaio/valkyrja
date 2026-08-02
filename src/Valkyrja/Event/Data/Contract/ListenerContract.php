<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Event\Data\Contract;

use Valkyrja\Container\Manager\Contract\ContainerContract;

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
     * Get the handler.
     *
     * @return callable(ContainerContract, array<string, mixed>):mixed
     */
    public function getHandler(): callable;

    /**
     * Create new listener with the specified handler.
     *
     * @param callable(ContainerContract, array<string, mixed>):mixed $handler The handler
     */
    public function withHandler(callable $handler): static;
}
