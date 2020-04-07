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

use function mb_convert_case;
use function mb_strtolower;
use function mb_strtoupper;
use function mb_substr;

/**
 * Class MbStr.
 *
 * @author Melech Mizrachi
 */
class MbStr extends Str
{
    /**
     * The charset to use.
     *
     * @var string
     */
    protected static string $charset = 'UTF-8';

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
        return mb_substr($string, $start, $length, static::$charset);
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
        return mb_convert_case($string, MB_CASE_TITLE, static::$charset);
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
        return mb_strtolower($string, static::$charset);
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
        return mb_strtoupper($string, static::$charset);
    }
}
