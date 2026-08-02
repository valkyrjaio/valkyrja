<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Validation\Throwable\Exception\Abstract;

use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;
use Valkyrja\Validation\Throwable\Contract\ValidationThrowable;

abstract class ValidationInvalidArgumentException extends ValkyrjaInvalidArgumentException implements ValidationThrowable
{
}
