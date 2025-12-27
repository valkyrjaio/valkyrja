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

namespace Valkyrja\Type\Uuid\Support;

use Override;
use Random\RandomException;
use RuntimeException;
use Valkyrja\Type\Uid\Support\Uid;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Exception\InvalidUuidException;

use function chr;
use function hexdec;
use function str_replace;
use function strlen;

/**
 * Class Uuid.
 *
 * @author Melech Mizrachi
 */
class Uuid extends Uid
{
    /** @var string */
    public const string REGEX = self::REGEX_PART . '{8}-'
        . self::REGEX_PART . '{4}-'
        . self::REGEX_PART . '{4}-'
        . self::REGEX_PART . '{4}-'
        . self::REGEX_PART . '{12}';

    /** @var Version */
    public const Version VERSION = Version::V1;

    /** @var string */
    protected const string REGEX_PART = '[0-9A-Fa-f]';

    /**
     * Generate v1 UUID.
     *
     * Version 1 UUIDs are time-based based. It can take an optional
     * node identifier based on mac address or a unique string id.
     *
     * @param string|null $node
     *
     * @throws RandomException
     *
     * @return string
     */
    final public static function v1(string|null $node = null): string
    {
        return UuidV1::generate($node);
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
    final public static function v3(string $namespace, string $name): string
    {
        return UuidV3::generate($namespace, $name);
    }

    /**
     * Generate a v4 UUID.
     *
     * @throws RandomException
     *
     * @return string
     */
    final public static function v4(): string
    {
        return UuidV4::generate();
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
    final public static function v5(string $namespace, string $name): string
    {
        return UuidV5::generate($namespace, $name);
    }

    /**
     * Generate a v6 UUID.
     *
     * @param string|null $node
     *
     * @throws RandomException
     *
     * @return string
     */
    final public static function v6(string|null $node = null): string
    {
        return UuidV6::generate($node);
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
        // NOTE: Purposefully left as UUID to ensure we accept any valid UUID, not just one that uses this method
        $uuidClass = self::class;
        $uuidClass::validate($uuid);

        // Get hexadecimal components of namespace
        $hex = str_replace(['-', '{', '}'], '', $uuid);
        // Binary Value
        $string = '';
        // The length of the namespace
        $length = strlen($hex);

        // Convert Namespace UUID to bits
        for ($i = 0; $i < $length; $i += 2) {
            $string .= chr((int) hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidUuidException("Invalid UUID $uid provided.");
    }
}
