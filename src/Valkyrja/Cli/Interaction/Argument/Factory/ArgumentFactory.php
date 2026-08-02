<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Interaction\Argument\Factory;

use Valkyrja\Cli\Interaction\Argument\Argument;

abstract class ArgumentFactory
{
    /**
     * @param non-empty-string $arg The arg
     */
    public static function fromArg(string $arg): Argument
    {
        return new Argument(value: $arg);
    }
}
