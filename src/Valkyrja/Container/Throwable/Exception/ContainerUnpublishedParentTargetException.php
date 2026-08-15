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

class ContainerUnpublishedParentTargetException extends ContainerRuntimeException
{
    /**
     * @param class-string $id The service id
     */
    public function __construct(string $id, int $code = 0, Throwable|null $previous = null)
    {
        $message = "`$id` is registered in the parent container and its publish callback has not run. "
            . 'Resolve or publish it in bootstrapParentServices(), or give the child the publish callbacks.';

        parent::__construct($message, $code, $previous);
    }
}
