<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Event\Attribute;

use Attribute;
use Valkyrja\Attribute\Contract\ReflectionAwareAttributeContract;
use Valkyrja\Attribute\Trait\ReflectionAwareAttribute;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Event\Data\Listener as Model;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Listener extends Model implements ReflectionAwareAttributeContract
{
    use ReflectionAwareAttribute;

    /**
     * @param class-string                                                   $eventId The event class name
     * @param non-empty-string                                               $name    A unique name for this listener
     * @param (callable(ContainerContract, array<string, mixed>):mixed)|null $handler The handler
     */
    public function __construct(
        protected string $eventId,
        protected string $name,
        callable|null $handler = null,
    ) {
        parent::__construct(
            eventId: $eventId,
            name: $name,
            handler: $handler ?? static fn (ContainerContract $container, array $arguments): null => null,
        );
    }
}
