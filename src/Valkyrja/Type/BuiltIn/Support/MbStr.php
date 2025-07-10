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

use Override;

use function mb_convert_case;
use function mb_strtolower;
use function mb_strtoupper;
use function mb_substr;

use const MB_CASE_TITLE;

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
     * @param string   $subject
     * @param int      $start
     * @param int|null $length
     *
     * @return string
     */
    #[Override]
    public static function substr(string $subject, int $start, int|null $length = null): string
    {
        return mb_substr($subject, $start, $length, static::$charset);
    }

    /**
     * Convert a string to title case.
     *
     * @param string $subject
     *
     * @return string
     */
    public static function toTitleCase(string $subject): string
    {
        return mb_convert_case($subject, MB_CASE_TITLE, static::$charset);
    }

    /**
     * Convert a string to lower case.
     *
     * @param string $subject
     *
     * @return string
     */
    public static function toLowerCase(string $subject): string
    {
        return mb_strtolower($subject, static::$charset);
    }

    /**
     * Convert a string to upper case.
     *
     * @param string $subject
     *
     * @return string
     */
    public static function toUpperCase(string $subject): string
    {
        return mb_strtoupper($subject, static::$charset);
    }
}
