<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Container\Throwable\Exception\Abstract;

use Valkyrja\Container\Throwable\Contract\ContainerThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaInvalidArgumentException;

abstract class ContainerInvalidArgumentException extends ValkyrjaInvalidArgumentException implements ContainerThrowable
{
}
