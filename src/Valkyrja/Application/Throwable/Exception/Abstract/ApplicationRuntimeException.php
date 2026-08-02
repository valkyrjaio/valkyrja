<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Application\Throwable\Exception\Abstract;

use Valkyrja\Application\Throwable\Contract\ApplicationThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

abstract class ApplicationRuntimeException extends ValkyrjaRuntimeException implements ApplicationThrowable
{
}
