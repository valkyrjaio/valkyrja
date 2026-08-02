<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Crypt\Throwable\Exception\Abstract;

use Valkyrja\Crypt\Throwable\Contract\CryptThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class CryptInvalidArgumentException extends ValkyrjaInvalidArgumentException implements CryptThrowable
{
}
