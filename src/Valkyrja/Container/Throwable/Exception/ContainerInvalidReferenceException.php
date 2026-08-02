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
use Valkyrja\Container\Throwable\Exception\Abstract\ContainerInvalidArgumentException;

class ContainerInvalidReferenceException extends ContainerInvalidArgumentException
{
    /**
     * @param class-string $id The invalid reference class name
     */
    public function __construct(string $id, int $code = 0, Throwable|null $previous = null)
    {
        $message = "Service with `$id` not found";

        parent::__construct($message, $code, $previous);
    }
}
