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
