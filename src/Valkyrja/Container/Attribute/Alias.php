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

namespace Valkyrja\Container\Attribute;

use Attribute;
use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;

/**
 * Attribute Alias.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Alias
{
    public ClassDispatch $dispatch;

    /**
     * @param class-string $serviceId The service id to attach to
     */
    public function __construct(
        public string $serviceId,
    ) {
    }

    public function withDispatch(ClassDispatch $dispatch): static
    {
        $new = clone $this;

        $new->dispatch = $dispatch;

        return $new;
    }
}
