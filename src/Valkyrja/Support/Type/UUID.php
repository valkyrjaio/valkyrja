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
use function ord;
use function preg_match;
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
     * Generate v1 UUID
     *
     * Version 1 UUIDs are time-based based. It can take an optional
     * node identifier based on mac address or a unique string id.
     *
     * @param string|null $node
     *
     * @throws Exception
     *
     * @return string
     */
    public static function v1(string $node = null): string
    {
        $node ??= random_bytes(16);
        // nano second time (only micro second precision) since start of UTC
        $time = microtime(true) * 10000000 + 0x01B21DD213814000;
        $time = pack('H*', sprintf('%016x', $time));

        $sequence    = random_bytes(2);
        $sequence[0] = chr(ord($sequence[0]) & 0x3F | 0x80);   // variant bits 10x
        $time[0]     = chr(ord($time[0]) & 0x0F | 0x10);           // version bits 0001

        if (! empty($node)) {
            // non hex string identifier
            if (preg_match('/[^a-f0-9]/is', $node)) {
                // base node off md5 hash for sequence
                $node = md5($node);
                // set multicast bit not IEEE 802 MAC
                $node = (hexdec(substr($node, 0, 2)) | 1) . substr($node, 2, 10);
            }

            if (is_numeric($node)) {
                $node = sprintf('%012x', $node);
            }

            $len = strlen($node);

            if ($len > 12) {
                $node = substr($node, 0, 12);
            } elseif ($len < 12) {
                $node .= str_repeat('0', 12 - $len);
            }
        } else {
            // base node off random sequence
            $node = random_bytes(6);
            // set multicast bit not IEEE 802 MAC
            $node[0] = chr(ord($node[0]) | 1);
            $node    = bin2hex($node);
        }

        return bin2hex($time[4] . $time[5] . $time[6] . $time[7])    // time low
            . '-' . bin2hex($time[2] . $time[3])                     // time med
            . '-' . bin2hex($time[0] . $time[1])                     // time hi
            . '-' . bin2hex($sequence)                                      // seq
            . '-' . $node;                                                  // node
    }

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
        $data = random_bytes(16);
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
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
     * Generate a v6 UUID.
     *
     * @param string|null $node
     *
     * @throws Exception
     *
     * @return string
     */
    public static function v6(string $node = null): string
    {
        $uuid     = self::v1($node);
        $uuid     = str_replace('-', '', $uuid);
        $timeLow1 = substr($uuid, 0, 5);
        $timeLow2 = substr($uuid, 5, 3);
        $timeMid  = substr($uuid, 8, 4);
        $timeHigh = substr($uuid, 13, 3);
        $rest     = substr($uuid, 16);

        return
            $timeHigh . $timeMid . $timeLow1[0] . '-' .
            substr($timeLow1, 1) . '-' .
            '6' . $timeLow2 . '-' .
            substr($rest, 0, 4) . '-' .
            substr($rest, 4);
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
