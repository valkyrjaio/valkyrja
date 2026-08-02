<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Session\Throwable\Exception\Abstract;

use Valkyrja\Session\Throwable\Contract\SessionThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class SessionInvalidArgumentException extends ValkyrjaInvalidArgumentException implements SessionThrowable
{
}
