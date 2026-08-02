<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Throwable\Exception\Abstract;

use Valkyrja\Cli\Throwable\Contract\CliThrowable;
use Valkyrja\Throwable\Exception\Abstract\ValkyrjaRuntimeException;

abstract class CliRuntimeException extends ValkyrjaRuntimeException implements CliThrowable
{
}
