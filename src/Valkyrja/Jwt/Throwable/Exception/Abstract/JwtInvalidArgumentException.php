<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Jwt\Throwable\Exception\Abstract;

use Valkyrja\Jwt\Throwable\Contract\JwtThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class JwtInvalidArgumentException extends ValkyrjaInvalidArgumentException implements JwtThrowable
{
}
