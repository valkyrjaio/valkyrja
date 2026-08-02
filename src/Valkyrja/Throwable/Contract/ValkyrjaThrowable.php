<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Throwable\Contract;

use Throwable;

interface ValkyrjaThrowable extends Throwable
{
    /**
     * Get trace code unique to the exception being thrown.
     *
     * @returns non-empty-string
     */
    public function getTraceCode(): string;
}
