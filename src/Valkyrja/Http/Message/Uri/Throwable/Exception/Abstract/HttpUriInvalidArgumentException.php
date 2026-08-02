<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Uri\Throwable\Exception\Abstract;

use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;
use Valkyrja\Http\Message\Uri\Throwable\Contract\HttpMessageThrowable;

abstract class HttpUriInvalidArgumentException extends HttpMessageInvalidArgumentException implements HttpMessageThrowable
{
}
