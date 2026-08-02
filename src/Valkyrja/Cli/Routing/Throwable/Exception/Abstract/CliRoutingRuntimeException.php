<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Throwable\Exception\Abstract;

use Valkyrja\Cli\Routing\Throwable\Contract\CliRoutingThrowable;
use Valkyrja\Cli\Throwable\Exception\Abstract\CliRuntimeException;

abstract class CliRoutingRuntimeException extends CliRuntimeException implements CliRoutingThrowable
{
}
