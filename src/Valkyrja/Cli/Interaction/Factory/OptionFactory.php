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
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;

use function strlen;

abstract class OptionFactory
{
    /**
     * @param non-empty-string $arg The arg
     *
     * @return Option[]
     */
    public static function fromArg(string $arg): array
    {
        if (! str_starts_with($arg, '-')) {
            throw new InvalidArgumentException('Options must begin with either a `-` or `--`');
        }

        $type = str_starts_with($arg, '--')
            ? OptionType::LONG
            : OptionType::SHORT;

        $parts = explode('=', $arg);
        $name  = trim($parts[0], '- ');
        $value = $parts[1] ?? null;

        if ($value === '') {
            $value = null;
        }

        if ($type === OptionType::SHORT && strlen($name) > 1) {
            if ($value !== null) {
                throw new InvalidArgumentException('Cannot combine multiple options and include a value');
            }

            $options = [];

            foreach (str_split($name) as $item) {
                /** @var non-empty-string $item */
                $options[] = new Option(
                    name: $item,
                    type: $type
                );
            }

            return $options;
        }

        /** @var non-empty-string $name */

        return [
            new Option(
                name: $name,
                value: $value,
                type: $type
            ),
        ];
    }
}
