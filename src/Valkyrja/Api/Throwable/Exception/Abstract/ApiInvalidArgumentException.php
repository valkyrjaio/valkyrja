<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Api\Throwable\Exception\Abstract;

use Valkyrja\Api\Throwable\Contract\ApiThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class ApiInvalidArgumentException extends ValkyrjaInvalidArgumentException implements ApiThrowable
{
}
