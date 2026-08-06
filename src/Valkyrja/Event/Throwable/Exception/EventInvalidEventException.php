<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Event\Throwable\Exception;

use Throwable;
use Valkyrja\Event\Throwable\Exception\Abstract\EventInvalidArgumentException;

class EventInvalidEventException extends EventInvalidArgumentException
{
    /**
     * @param class-string $id The event id that the container resolves to a different type
     */
    public function __construct(string $id, int $code = 0, Throwable|null $previous = null)
    {
        $message = "Service with `$id` is not an event";

        parent::__construct($message, $code, $previous);
    }
}
