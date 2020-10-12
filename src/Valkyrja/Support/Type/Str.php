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
use function filter_var;
use function preg_replace;
use function random_bytes;
use function str_replace;
use function strlen;
use function strncmp;
use function strpos;
use function strtolower;
use function strtoupper;
use function substr;
use function trim;
use function ucwords;

use const FILTER_VALIDATE_EMAIL;

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
     * @param string $subject The subject
     * @param string $needle  The needle
     *
     * @return bool
     */
    public static function startsWith(string $subject, string $needle): bool
    {
        return $needle !== '' && strncmp($subject, $needle, strlen($needle)) === 0;
    }

    /**
     * Check if a string starts with any needle.
     *
     * @param string $subject    The subject
     * @param string ...$needles The needles
     *
     * @return bool
     */
    public static function startsWithAny(string $subject, string ...$needles): bool
    {
        return static::withAny('startsWith', $subject, ...$needles);
    }

    /**
     * Check if a string ends with a needle.
     *
     * @param string $subject The subject
     * @param string $needle  The needle
     *
     * @return bool
     */
    public static function endsWith(string $subject, string $needle): bool
    {
        $needleLen = strlen($needle);

        return $needle !== '' && strncmp(static::substr($subject, -$needleLen, $needleLen), $needle, $needleLen) === 0;
    }

    /**
     * Check if a string ends with any needle.
     *
     * @param string $subject    The subject
     * @param string ...$needles The needles
     *
     * @return bool
     */
    public static function endsWithAny(string $subject, string ...$needles): bool
    {
        return static::withAny('endsWith', $subject, ...$needles);
    }

    /**
     * Check if a string contains a needle.
     *
     * @param string $subject The subject
     * @param string $needle  The needle
     *
     * @return bool
     */
    public static function contains(string $subject, string $needle): bool
    {
        return strpos($subject, $needle) !== false;
    }

    /**
     * Check if a string contains any needle.
     *
     * @param string $subject    The subject
     * @param string ...$needles The needles
     *
     * @return bool
     */
    public static function containsAny(string $subject, string ...$needles): bool
    {
        return static::withAny('contains', $subject, ...$needles);
    }

    /**
     * Check if a string's length is longer than a minimum length.
     *
     * @param string $subject The subject
     * @param int    $min     [optional] The minimum length
     *
     * @return bool
     */
    public static function min(string $subject, int $min = 0): bool
    {
        return strlen($subject) > $min;
    }

    /**
     * Check if a string's length is not longer than a maximum length.
     *
     * @param string $subject The subject
     * @param int    $max     [optional] The max length
     *
     * @return bool
     */
    public static function max(string $subject, int $max = 255): bool
    {
        return strlen($subject) < $max;
    }

    /**
     * Replace a portion of a string with a replacement.
     *
     * @param string $subject     The subject
     * @param string $replace     The needle to replace
     * @param string $replacement The replacement
     *
     * @return string
     */
    public static function replace(string $subject, string $replace, string $replacement): string
    {
        return str_replace($replace, $replacement, $subject);
    }

    /**
     * Replace any portion of a string with any replacement.
     *
     * @param string   $subject     The subject
     * @param string[] $replace     The needles to replace
     * @param string[] $replacement The replacements
     *
     * @return string
     */
    public static function replaceAll(string $subject, array $replace, array $replacement): string
    {
        static::validateStrings(...$replace);
        static::validateStrings(...$replacement);

        return str_replace($replace, $replacement, $subject);
    }

    /**
     * Replace all portions of a string with a replacement.
     *
     * @param string   $subject     The subject
     * @param string[] $replace     The needles to replace
     * @param string   $replacement The replacement
     *
     * @return string
     */
    public static function replaceAllWith(string $subject, array $replace, string $replacement): string
    {
        static::validateStrings(...$replace);

        return str_replace($replace, $replacement, $subject);
    }

    /**
     * Get a substring from start position with a certain length.
     *
     * @param string   $subject The subject
     * @param int      $start   The start
     * @param int|null $length  [optional] The length
     *
     * @return string
     */
    public static function substr(string $subject, int $start, int $length = null): string
    {
        return substr($subject, $start, $length);
    }

    /**
     * Convert a string to title case.
     *
     * @param string $subject The subject
     *
     * @return string
     */
    public static function toTitleCase(string $subject): string
    {
        return ucwords($subject);
    }

    /**
     * Convert all strings to title case.
     *
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    public static function allToTitleCase(string ...$subjects): array
    {
        return static::allTo('toTitleCase', ...$subjects);
    }

    /**
     * Convert a string to lower case.
     *
     * @param string $subject The subject
     *
     * @return string
     */
    public static function toLowerCase(string $subject): string
    {
        return strtolower($subject);
    }

    /**
     * Convert all strings to lower case.
     *
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    public static function allToLowerCase(string ...$subjects): array
    {
        return static::allTo('toLowerCase', ...$subjects);
    }

    /**
     * Convert a string to upper case.
     *
     * @param string $subject The subject
     *
     * @return string
     */
    public static function toUpperCase(string $subject): string
    {
        return strtoupper($subject);
    }

    /**
     * Convert all strings to upper case.
     *
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    public static function allToUpperCase(string ...$subjects): array
    {
        return static::allTo('toUpperCase', ...$subjects);
    }

    /**
     * Convert a string to capitalized.
     *
     * @param string      $subject The subject
     * @param string|null $delimiter [optional] The delimiter
     *
     * @return string
     */
    public static function toCapitalized(string $subject, string $delimiter = null): string
    {
        if ($delimiter) {
            return ucwords($subject, $delimiter);
        }

        return ucwords($subject);
    }

    /**
     * Convert all strings to capitalized.
     *
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    public static function allToCapitalized(string ...$subjects): array
    {
        return static::allTo('toUpperCase', ...$subjects);
    }

    /**
     * Convert a string to snake case.
     *
     * @param string $subject The subject
     *
     * @return string
     */
    public static function toSnakeCase(string $subject): string
    {
        $key = $subject;

        if (isset(static::$snakeCache[$key])) {
            return static::$snakeCache[$key];
        }

        if (! ctype_lower($subject)) {
            $subject = preg_replace('/\s+/u', '', ucwords($subject));

            $subject = static::toLowerCase(preg_replace('/(.)(?=[A-Z])/u', '$1_', $subject));
        }

        return static::$snakeCache[$key] = $subject;
    }

    /**
     * Convert all string to snake case.
     *
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    public static function allToSnakeCase(string ...$subjects): array
    {
        return static::allTo('toSnakeCase', ...$subjects);
    }

    /**
     * Convert a string to studly case.
     *
     * @param string $subject The subject
     *
     * @return string
     */
    public static function toStudlyCase(string $subject): string
    {
        $key = $subject;

        if (isset(self::$studlyCache[$key])) {
            return self::$studlyCache[$key];
        }

        $subject = static::toCapitalized(static::replaceAllWith($subject, ['-', '_'], ' '));

        return self::$studlyCache[$key] = static::replace($subject, ' ', '');
    }

    /**
     * Convert all string to studly case.
     *
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    public static function allToStudlyCase(string ...$subjects): array
    {
        return static::allTo('toStudlyCase', ...$subjects);
    }

    /**
     * Convert a string's first character to upper case.
     *
     * @param string $subject The subject
     *
     * @return string
     */
    public static function ucFirstLetter(string $subject): string
    {
        return static::toUpperCase(static::substr($subject, 0, 1)) . static::substr($subject, 1);
    }

    /**
     * Convert all strings' first characters to upper case.
     *
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    public static function allUcFirstLetter(string ...$subjects): array
    {
        return static::allTo('ucFirstLetter', ...$subjects);
    }

    /**
     * Get a random string.
     *
     * @param int $length [optional] The length
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
     * @param mixed ...$subjects The subjects
     *
     * @return void
     */
    public static function validateStrings(string ...$subjects): void
    {
        // Left empty on purpose. Validation happens due to PHP type hinting.
    }

    /**
     * Check if a string is a valid email.
     *
     * @param string $subject The subject
     *
     * @return bool
     */
    public static function isEmail(string $subject): bool
    {
        return (bool) filter_var($subject, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Convert all strings to a method output.
     *
     * @param string $method      The method to call for each subject
     * @param string ...$subjects The subjects
     *
     * @return array
     */
    protected static function allTo(string $method, string ...$subjects): array
    {
        foreach ($subjects as $key => $string) {
            $subjects[$key] = static::$$method($string);
        }

        return $subjects;
    }

    /**
     * Check if a string matches a method constraint with any needle.
     *
     * @param string $method     The method to check all needles against
     * @param string $subject    The subject
     * @param string ...$needles The needles
     *
     * @return bool
     */
    protected static function withAny(string $method, string $subject, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (static::$$method($subject, $needle)) {
                return true;
            }
        }

        return false;
    }
}
