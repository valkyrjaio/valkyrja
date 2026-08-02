<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Throwable\Exception\Abstract;

use Override;
use RuntimeException;
use Valkyrja\Throwable\Contract\ValkyrjaThrowable;
use Valkyrja\Throwable\Factory\ThrowableFactory;

abstract class ValkyrjaRuntimeException extends RuntimeException implements ValkyrjaThrowable
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getTraceCode(): string
    {
        return ThrowableFactory::getTraceCode($this);
    }
}
