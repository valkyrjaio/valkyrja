<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Throwable\Factory;

use Throwable;

use function md5;

class ThrowableFactory
{
    /**
     * Get the trace code for a throwable.
     *
     * @param Throwable $throwable The throwable
     */
    public static function getTraceCode(Throwable $throwable): string
    {
        return md5($throwable::class . $throwable->getTraceAsString());
    }
}
