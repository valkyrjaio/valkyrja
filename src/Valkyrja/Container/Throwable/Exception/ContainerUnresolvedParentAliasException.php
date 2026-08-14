<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Container\Throwable\Exception;

use Throwable;
use Valkyrja\Container\Throwable\Exception\Abstract\ContainerRuntimeException;

class ContainerUnresolvedParentAliasException extends ContainerRuntimeException
{
    /**
     * @param class-string $alias     The alias the parent declares
     * @param class-string $aliasedId The id the alias walk reached
     */
    public function __construct(
        string $alias,
        string $aliasedId,
        int $code = 0,
        Throwable|null $previous = null
    ) {
        $message = "Alias `$alias` reaches `$aliasedId`, which the parent container has not resolved. "
            . 'Force-resolve it in bootstrapParentServices() before the request loop begins.';

        parent::__construct($message, $code, $previous);
    }
}
