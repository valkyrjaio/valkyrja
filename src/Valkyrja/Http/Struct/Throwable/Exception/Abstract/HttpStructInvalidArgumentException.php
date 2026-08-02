<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Struct\Throwable\Exception\Abstract;

use Valkyrja\Http\Struct\Throwable\Contract\HttpStructThrowable;
use Valkyrja\Http\Throwable\Exception\Abstract\HttpInvalidArgumentException;

abstract class HttpStructInvalidArgumentException extends HttpInvalidArgumentException implements HttpStructThrowable
{
}
