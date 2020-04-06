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
use function random_bytes;
use function strlen;
use function strncmp;
use function substr;
use function trim;

/**
 * Class Str.
 *
 * @author Melech Mizrachi
 */
class Str
{
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

        return $needle !== '' && strncmp(substr($string, -$needleLen, $needleLen), $needle, $needleLen) === 0;
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
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
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
        return mb_strtolower($string, 'UTF-8');
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
        return mb_strtoupper($string, 'UTF-8');
    }

    /**
     * Get a token.
     *
     * @param int $length
     *
     * @throws Exception
     *
     * @return string
     */
    public static function generateToken(int $length = 20): string
    {
        return trim(base64_encode(random_bytes($length)), " \t\n\r\0\x0B/");
    }
}
