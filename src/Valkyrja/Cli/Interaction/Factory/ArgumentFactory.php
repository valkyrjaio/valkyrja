<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Cli\Interaction\Factory;

use Valkyrja\Cli\Interaction\Argument\Argument;

/**
 * Class ArgumentFactory.
 */
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
