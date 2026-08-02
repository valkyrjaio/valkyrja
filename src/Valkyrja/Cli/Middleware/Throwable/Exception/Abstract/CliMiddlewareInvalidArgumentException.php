<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Throwable\Exception\Abstract;

use Valkyrja\Cli\Middleware\Throwable\Contract\CliMiddlewareThrowable;
use Valkyrja\Cli\Throwable\Exception\Abstract\CliInvalidArgumentException;

abstract class CliMiddlewareInvalidArgumentException extends CliInvalidArgumentException implements CliMiddlewareThrowable
{
}
