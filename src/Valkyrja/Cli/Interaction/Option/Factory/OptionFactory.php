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

namespace Valkyrja\Cli\Interaction\Option\Factory;

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
        self::validateArgIsOption($arg);

        $type = self::getOptionType($arg);

        $parts = explode('=', $arg);
        $name  = trim($parts[0], '- ');
        $value = $parts[1] ?? null;

        if ($value === '') {
            $value = null;
        }

        self::validateNonEmptyName($name);

        // Finds short options combined together
        // e.g. -abc instead of -a -b -c
        if ($type === OptionType::SHORT && strlen($name) > 1) {
            self::validateValueIsEmpty($value);

            return self::splitCombinedShortOptions($type, $name);
        }

        return [
            new Option(
                name: $name,
                value: $value,
                type: $type
            ),
        ];
    }

    /**
     * Validate that an arg is an option.
     *
     * @param non-empty-string $arg The arg
     */
    protected static function validateArgIsOption(string $arg): void
    {
        if (! str_starts_with($arg, '-')) {
            throw new InvalidArgumentException('Options must begin with either a `-` or `--`');
        }
    }

    /**
     * Validate that an option name is not empty.
     *
     * @psalm-assert non-empty-string $name
     *
     * @phpstan-assert non-empty-string $name
     */
    protected static function validateNonEmptyName(string $name): void
    {
        if ($name === '') {
            throw new InvalidArgumentException('Option name cannot be empty');
        }
    }

    /**
     * Get the type of option based on the name prefix.
     *
     * @param non-empty-string $arg The arg
     */
    protected static function getOptionType(string $arg): OptionType
    {
        return str_starts_with($arg, '--')
            ? OptionType::LONG
            : OptionType::SHORT;
    }

    /**
     * Validate that a value is not provided when multiple options are provided.
     *
     * @param string|null $value The value
     */
    protected static function validateValueIsEmpty(string|null $value = null): void
    {
        if ($value !== null) {
            throw new InvalidArgumentException('Cannot combine multiple options and include a value');
        }
    }

    /**
     * Split a combined short option into individual options.
     *
     * @param OptionType       $type The type
     * @param non-empty-string $name The name to split
     *
     * @return Option[]
     */
    protected static function splitCombinedShortOptions(OptionType $type, string $name): array
    {
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
}
