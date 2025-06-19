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
 * Attribute Context.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Context
{
    public ClassDispatch $dispatch;

    /**
     * @param class-string          $serviceId         The service id to attach to
     * @param class-string          $contextClassName  The context class name
     * @param non-empty-string|null $contextMemberName The context member
     */
    public function __construct(
        public string $serviceId,
        public string $contextClassName,
        public string|null $contextMemberName = null,
    ) {
    }

    public function withDispatch(ClassDispatch $dispatch): static
    {
        $new = clone $this;

        $new->dispatch = $dispatch;

        return $new;
    }
}
