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

namespace Valkyrja\Support\Type;

use Exception;

use function base64_encode;
use function preg_replace;
use function random_bytes;
use function str_replace;
use function strlen;
use function strncmp;
use function strtolower;
use function strtoupper;
use function substr;
use function trim;
use function ucwords;

/**
 * Class Str.
 *
 * @author Melech Mizrachi
 */
class Str
{
    /**
     * Studly case conversion cache.
     *
     * @var array
     */
    protected static array $studlyCache = [];

    /**
     * Snake case conversion cache.
     *
     * @var array
     */
    protected static array $snakeCache = [];

    /**
     * Check if a string starts with a needle.
     *
     * @param string $string
     * @param string $needle
     *
     * @return bool
     */
    public static function startsWith(string $string, string $needle): bool
    {
        return $needle !== '' && strncmp($string, $needle, strlen($needle)) === 0;
    }

    /**
     * Check if a string starts with any needle.
     *
     * @param string $string
     * @param string ...$needles
     *
     * @return bool
     */
    public static function startsWithAny(string $string, string ...$needles): bool
    {
        return static::withAny('startsWith', $string, ...$needles);
    }

    /**
     * Check if a string ends with a needle.
     *
     * @param string $string
     * @param string $needle
     *
     * @return bool
     */
    public static function endsWith(string $string, string $needle): bool
    {
        $needleLen = strlen($needle);

        return $needle !== '' && strncmp(static::substr($string, -$needleLen, $needleLen), $needle, $needleLen) === 0;
    }

    /**
     * Check if a string ends with any needle.
     *
     * @param string $string
     * @param string ...$needles
     *
     * @return bool
     */
    public static function endsWithAny(string $string, string ...$needles): bool
    {
        return static::withAny('endsWith', $string, ...$needles);
    }

    /**
     * Replace a portion of a string with a replacement.
     *
     * @param string $string
     * @param string $replace
     * @param string $replacement
     *
     * @return string
     */
    public static function replace(string $string, string $replace, string $replacement): string
    {
        return str_replace($replace, $replacement, $string);
    }

    /**
     * Replace any portion of a string with any replacement.
     *
     * @param string   $string
     * @param string[] $replace
     * @param string[] $replacement
     *
     * @return string
     */
    public static function replaceAny(string $string, array $replace, array $replacement): string
    {
        static::validateStrings(...$replace);
        static::validateStrings(...$replacement);

        return str_replace($replace, $replacement, $string);
    }

    /**
     * Replace all portions of a string with a replacement.
     *
     * @param string   $string
     * @param string[] $replace
     * @param string   $replacement
     *
     * @return string
     */
    public static function replaceAll(string $string, array $replace, string $replacement): string
    {
        static::validateStrings(...$replace);

        return str_replace($replace, $replacement, $string);
    }

    /**
     * Get a substring from start position with a certain length.
     *
     * @param string   $string
     * @param int      $start
     * @param int|null $length
     *
     * @return string
     */
    public static function substr(string $string, int $start, int $length = null): string
    {
        return substr($string, $start, $length);
    }

    /**
     * Convert a string to title case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function toTitleCase(string $string): string
    {
        return ucwords($string);
    }

    /**
     * Convert all strings to title case.
     *
     * @param string ...$strings
     *
     * @return array
     */
    public static function allToTitleCase(string ...$strings): array
    {
        return static::allTo('toTitleCase', ...$strings);
    }

    /**
     * Convert a string to lower case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function toLowerCase(string $string): string
    {
        return strtolower($string);
    }

    /**
     * Convert all strings to lower case.
     *
     * @param string ...$strings
     *
     * @return array
     */
    public static function allToLowerCase(string ...$strings): array
    {
        return static::allTo('toLowerCase', ...$strings);
    }

    /**
     * Convert a string to upper case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function toUpperCase(string $string): string
    {
        return strtoupper($string);
    }

    /**
     * Convert all strings to upper case.
     *
     * @param string ...$strings
     *
     * @return array
     */
    public static function allToUpperCase(string ...$strings): array
    {
        return static::allTo('toUpperCase', ...$strings);
    }

    /**
     * Convert a string to snake case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function toSnakeCase(string $string): string
    {
        $key = $string;

        if (isset(static::$snakeCache[$key])) {
            return static::$snakeCache[$key];
        }

        if (! ctype_lower($string)) {
            $string = preg_replace('/\s+/u', '', ucwords($string));

            $string = static::toLowerCase(preg_replace('/(.)(?=[A-Z])/u', '$1_', $string));
        }

        return static::$snakeCache[$key] = $string;
    }

    /**
     * Convert all string to snake case.
     *
     * @param string ...$strings
     *
     * @return array
     */
    public static function allToSnakeCase(string ...$strings): array
    {
        return static::allTo('toSnakeCase', ...$strings);
    }

    /**
     * Convert a string to studly case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function toStudlyCase(string $string): string
    {
        $key = $string;

        if (isset(self::$studlyCache[$key])) {
            return self::$studlyCache[$key];
        }

        $string = static::toUpperCase(static::replaceAll($string, ['-', '_'], ' '));

        return self::$studlyCache[$key] = static::replace($string, ' ', '');
    }

    /**
     * Convert all string to studly case.
     *
     * @param string ...$strings
     *
     * @return array
     */
    public static function allToStudlyCase(string ...$strings): array
    {
        return static::allTo('toStudlyCase', ...$strings);
    }

    /**
     * Convert a string's first character to upper case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function ucFirstLetter(string $string): string
    {
        return static::toUpperCase(static::substr($string, 0, 1)) . static::substr($string, 1);
    }

    /**
     * Convert all strings' first characters to upper case.
     *
     * @param string ...$strings
     *
     * @return array
     */
    public static function allUcFirstLetter(string ...$strings): array
    {
        return static::allTo('ucFirstLetter', ...$strings);
    }

    /**
     * Get a random string.
     *
     * @param int $length
     *
     * @throws Exception
     *
     * @return string
     */
    public static function random(int $length = 20): string
    {
        return trim(base64_encode(random_bytes($length)), " \t\n\r\0\x0B/");
    }

    /**
     * Validate multiple params to all be strings by using PHPs built in type hinting.
     *
     * @param mixed ...$strings
     *
     * @return void
     */
    public static function validateStrings(string ...$strings): void
    {
        // Left empty on purpose. Validation happens due to PHP type hinting.
    }

    /**
     * Convert all strings to a method output.
     *
     * @param string $method
     * @param string ...$strings
     *
     * @return array
     */
    protected static function allTo(string $method, string ...$strings): array
    {
        foreach ($strings as $key => $string) {
            $strings[$key] = static::$$method($string);
        }

        return $strings;
    }

    /**
     * Check if a string matches a method constraint with any needle.
     *
     * @param string $method
     * @param string $string
     * @param string ...$needles
     *
     * @return bool
     */
    protected static function withAny(string $method, string $string, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (static::$$method($string, $needle)) {
                return true;
            }
        }

        return false;
    }
}
