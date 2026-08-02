<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Throwable\Exception\Abstract;

use Valkyrja\Cli\Interaction\Throwable\Contract\CliInteractionThrowable;
use Valkyrja\Cli\Throwable\Exception\Abstract\CliRuntimeException;

abstract class CliInteractionRuntimeException extends CliRuntimeException implements CliInteractionThrowable
{
}
