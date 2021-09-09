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
use RuntimeException;

use function chr;
use function hexdec;
use function md5;
use function preg_match;
use function random_int;
use function sha1;
use function sprintf;
use function str_replace;
use function strlen;
use function substr;

/**
 * Class UUID.
 *
 * @author Melech Mizrachi
 */
class UUID
{
    /**
     * Generate a v3 UUID.
     *
     * @param string $namespace
     * @param string $name
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public static function v3(string $namespace, string $name): string
    {
        // Calculate hash value
        $hash = md5(static::convertToBits($namespace) . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',

            // 32 bits for "time_low"
            substr($hash, 0, 8),

            // 16 bits for "time_mid"
            substr($hash, 8, 4),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 3
            (hexdec(substr($hash, 12, 4)) & 0x0FFF) | 0x3000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3FFF) | 0x8000,

            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    /**
     * Generate a v4 UUID.
     *
     * @throws Exception
     *
     * @return string
     */
    public static function v4(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),

            // 16 bits for "time_mid"
            random_int(0, 0xFFFF),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            random_int(0, 0x0FFF) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            random_int(0, 0x3FFF) | 0x8000,

            // 48 bits for "node"
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF),
            random_int(0, 0xFFFF)
        );
    }

    /**
     * Generate a v5 UUID.
     *
     * @param string $namespace
     * @param string $name
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public static function v5(string $namespace, string $name): string
    {
        // Calculate hash value
        $hash = sha1(static::convertToBits($namespace) . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',

            // 32 bits for "time_low"
            substr($hash, 0, 8),

            // 16 bits for "time_mid"
            substr($hash, 8, 4),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 5
            (hexdec(substr($hash, 12, 4)) & 0x0FFF) | 0x5000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3FFF) | 0x8000,

            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    /**
     * Check if a UUID is valid.
     *
     * @param string $uuid
     *
     * @return bool
     */
    public static function isValid(string $uuid): bool
    {
        return preg_match('/^{?[0-9a-f]{8}-?[0-9a-f]{4}-?[0-9a-f]{4}-?[0-9a-f]{4}-?[0-9a-f]{12}}?$/i', $uuid) === 1;
    }

    /**
     * Validate a UUID.
     *
     * @param string $uuid
     *
     * @throws RuntimeException
     *
     * @return void
     */
    public static function validateUUID(string $uuid): void
    {
        if (! self::isValid($uuid)) {
            throw new RuntimeException('Invalid namespace provided.');
        }
    }

    /**
     * Convert a UUID to bits.
     *
     * @param string $uuid
     *
     * @return string
     */
    protected static function convertToBits(string $uuid): string
    {
        static::validateUUID($uuid);

        // Get hexadecimal components of namespace
        $hex = str_replace(['-', '{', '}'], '', $uuid);
        // Binary Value
        $string = '';
        // The length of the namespace
        $length = strlen($hex);

        // Convert Namespace UUID to bits
        for ($i = 0; $i < $length; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }
}
