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

namespace Valkyrja\Type\BuiltIn\Support;

use BackedEnum;
use UnitEnum;
use Valkyrja\Type\Throwable\Exception\InvalidArgumentException;

use function getType;

/**
 * Class ArrayOf.
 */
class ArrayOf
{
    /**
     * Check an array to ensure it is all strings.
     */
    public static function strings(string ...$values): void
    {
    }

    /**
     * Check an array to ensure it is all integers.
     */
    public static function ints(int ...$values): void
    {
    }

    /**
     * Check an array to ensure it is all floats.
     */
    public static function floats(float ...$values): void
    {
    }

    /**
     * Check an array to ensure it is all booleans.
     */
    public static function booleans(bool ...$values): void
    {
    }

    /**
     * Check an array to ensure it is all true.
     */
    public static function true(bool ...$values): void
    {
        foreach ($values as $key => $value) {
            if (! $value) {
                throw new InvalidArgumentException("Argument $key must be of type true, false given");
            }
        }
    }

    /**
     * Check an array to ensure it is all false.
     */
    public static function false(bool ...$values): void
    {
        foreach ($values as $key => $value) {
            if ($value) {
                throw new InvalidArgumentException("Argument $key must be of type false, true given");
            }
        }
    }

    /**
     * Check an array to ensure it is all null.
     */
    public static function null(mixed ...$values): void
    {
        foreach ($values as $key => $value) {
            if ($value !== null) {
                $type = gettype($key);

                throw new InvalidArgumentException("Argument $key must be of type null, $type given");
            }
        }
    }

    /**
     * Check an array to ensure it is all arrays.
     *
     * @param array<array-key, mixed> ...$values The arrays
     */
    public static function arrays(array ...$values): void
    {
    }

    /**
     * Check an array to ensure it is all objects.
     */
    public static function objects(object ...$values): void
    {
    }

    /**
     * Check an array to ensure it is all enums.
     */
    public static function enums(UnitEnum ...$values): void
    {
    }

    /**
     * Check an array to ensure it is all backed enums.
     */
    public static function backedEnums(BackedEnum ...$values): void
    {
    }
}
