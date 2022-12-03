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

namespace Valkyrja\Type;

use BackedEnum;
use UnitEnum;

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
     * @param string $enum The enum class name
     *
     * @return string[]
     */
    public static function names(string $enum): array
    {
        $cacheName = "names$enum";

        if (isset(self::$namesCache[$cacheName])) {
            return self::$namesCache[$cacheName];
        }

        Cls::validateInherits($enum, UnitEnum::class);

        /** @var UnitEnum|string $enum */

        return self::$namesCache[$cacheName] = array_column($enum::cases(), 'name');
    }

    /**
     * Get enum case values.
     *
     * @param string $enum The enum class name
     *
     * @return string[]|int[]
     */
    public static function values(string $enum): array
    {
        $cacheName = "values$enum";

        if (isset(self::$cache[$cacheName])) {
            return self::$cache[$cacheName];
        }

        Cls::validateInherits($enum, UnitEnum::class);

        if (Cls::inherits($enum, BackedEnum::class)) {
            /** @var BackedEnum|string $enum */
            return self::$cache[$cacheName] = array_column($enum::cases(), 'value');
        }

        return self::$cache[$cacheName] = self::names($enum);
    }

    /**
     * Get enum as an array with name as index and value as value.
     *
     * @param string $enum The enum class name
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
     * @param string $enum The enum class name
     *
     * @return array<int|string, string>
     */
    public static function asReverseArray(string $enum): array
    {
        return array_combine(self::values($enum), self::names($enum));
    }
}
