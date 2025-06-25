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

use Valkyrja\Cli\Interaction\Enum\OptionType;
use Valkyrja\Cli\Interaction\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Option\Option;

/**
 * Class OptionFactory.
 *
 * @author Melech Mizrachi
 */
abstract class OptionFactory
{
    /**
     * @param non-empty-string $arg The arg
     */
    public static function fromArg(string $arg): Option
    {
        if (! str_starts_with($arg, '-')) {
            throw new InvalidArgumentException('Options must begin with either a `-` or `--`');
        }

        $type = str_starts_with($arg, '--')
            ? OptionType::LONG
            : OptionType::SHORT;

        $parts = explode('=', trim($arg, '- '));

        $name  = $parts[0];
        $value = $parts[1] ?? null;

        if ($value === '') {
            $value = null;
        }

        return new Option(
            name: $name,
            value: $value,
            type: $type
        );
    }
}
