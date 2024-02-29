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

namespace Valkyrja\Type\Support;

use BackedEnum;
use UnitEnum;

use function assert;
use function in_array;

/**
 * Class Enum.
 *
 * @author Melech Mizrachi
 */
class Enum
{
    /**
     * A cache of enum class names and their names, or values.
     *
     * @var array<string, string[]|int[]>
     */
    protected static array $cache = [];

    /**
     * A cache of enum class names and their names, or values.
     *
     * @var array<string, string[]>
     */
    protected static array $namesCache = [];

    /**
     * Get enum case names.
     *
     * @param class-string<UnitEnum> $enum The enum class name
     *
     * @return string[]
     */
    public static function names(string $enum): array
    {
        $cacheName = "names$enum";

        if (isset(self::$namesCache[$cacheName])) {
            return self::$namesCache[$cacheName];
        }

        assert(is_a($enum, UnitEnum::class, true));

        return self::$namesCache[$cacheName] = array_column($enum::cases(), 'name');
    }

    /**
     * Get enum case values.
     *
     * @param class-string<UnitEnum> $enum The enum class name
     *
     * @return string[]|int[]
     */
    public static function values(string $enum): array
    {
        $cacheName = "values$enum";

        if (isset(self::$cache[$cacheName])) {
            return self::$cache[$cacheName];
        }

        assert(is_a($enum, UnitEnum::class, true));

        if (is_a($enum, BackedEnum::class, true)) {
            /** @var int[]|string[] $values */
            $values = array_column($enum::cases(), 'value');

            return self::$cache[$cacheName] = $values;
        }

        return self::$cache[$cacheName] = self::names($enum);
    }

    /**
     * Get enum as an array with name as index and value as value.
     *
     * @param class-string<UnitEnum> $enum The enum class name
     *
     * @return array<string, int|string>
     */
    public static function asArray(string $enum): array
    {
        return array_combine(self::names($enum), self::values($enum));
    }

    /**
     * Get enum as an array with value as index and name as value.
     *
     * @param class-string<UnitEnum> $enum The enum class name
     *
     * @return array<int|string, string>
     */
    public static function asReverseArray(string $enum): array
    {
        return array_combine(self::values($enum), self::names($enum));
    }

    /**
     * Determine whether a name is valid for a given enum.
     *
     * @param class-string<UnitEnum> $enum The enum class name
     * @param string                 $name The name to check
     *
     * @return bool
     */
    public static function isValidName(string $enum, string $name): bool
    {
        return in_array($name, self::names($enum), true);
    }

    /**
     * Determine whether a value is valid for a given enum.
     *
     * @param class-string<BackedEnum> $enum  The enum class name
     * @param string|int               $value The value to check
     *
     * @return bool
     */
    public static function isValidValue(string $enum, string|int $value): bool
    {
        return in_array($value, self::values($enum), true);
    }
}
