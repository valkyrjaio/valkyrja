<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Stream\Throwable\Exception\Abstract;

use Valkyrja\Http\Message\Stream\Throwable\Contract\HttpStreamThrowable;
use Valkyrja\Http\Message\Throwable\Exception\Abstract\HttpMessageInvalidArgumentException;

abstract class HttpStreamInvalidArgumentException extends HttpMessageInvalidArgumentException implements HttpStreamThrowable
{
}
