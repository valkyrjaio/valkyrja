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

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ListenerHandler implements ReflectionAwareAttributeContract
{
    use ReflectionAwareAttribute;

    /** @var callable(ContainerContract, array<string, mixed>):mixed */
    public $handler;

    /**
     * @param callable(ContainerContract, array<string, mixed>):mixed $handler The handler
     */
    public function __construct(
        callable $handler,
    ) {
        $this->handler = $handler;
    }
}
