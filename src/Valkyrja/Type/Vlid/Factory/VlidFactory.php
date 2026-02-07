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

namespace Valkyrja\Type\Vlid\Factory;

use DateTimeInterface;
use InvalidArgumentException;
use Override;
use Random\RandomException;
use Valkyrja\Type\Throwable\Exception\RuntimeException;
use Valkyrja\Type\Ulid\Factory\UlidFactory;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidException;

use function sprintf;

/**
 * Valkyrja Universally Unique Lexicographically Sortable Identifier (VLID)
 * A more precise version of a ULID where time must be down to the microsecond, and can sacrifice on randomness a
 * little while remaining 128 bit.
 */
class VlidFactory extends UlidFactory
{
    /** @var string */
    public const string REGEX = '[0-7]'
        . '[' . self::VALID_CHARACTERS . ']{12}'
        . '[1-4]'
        . '[' . self::VALID_CHARACTERS . ']{4}'
        . '([' . self::VALID_CHARACTERS . ']{4})?'
        . '([' . self::VALID_CHARACTERS . ']{4})?'
        . '([' . self::VALID_CHARACTERS . ']{4})?';

    /** @var Version */
    public const Version VERSION = Version::V1;

    /** @var string */
    protected const string FORMAT = '%013s%01s%04s%04s%04s';

    /** @var int */
    protected const int MAX_RANDOM_BYTES = 3;

    /** @var string */
    protected static string $time = '';

    /** @var array<int, int> */
    protected static array $randomBytes = [];

    /**
     * Generate a VLID v1.
     *
     * @param DateTimeInterface|null $dateTime  [optional] The date time to use when generating the ULID
     * @param bool                   $lowerCase [optional] Whether to return as lower case
     *
     * @throws InvalidArgumentException
     * @throws RandomException
     * @throws RuntimeException
     */
    final public static function v1(DateTimeInterface|null $dateTime = null, bool $lowerCase = false): string
    {
        return VlidV1Factory::generate($dateTime, $lowerCase);
    }

    /**
     * Generate a VLID v2.
     *
     * @param DateTimeInterface|null $dateTime  [optional] The date time to use when generating the ULID
     * @param bool                   $lowerCase [optional] Whether to return as lower case
     *
     * @throws InvalidArgumentException
     * @throws RandomException
     * @throws RuntimeException
     */
    final public static function v2(DateTimeInterface|null $dateTime = null, bool $lowerCase = false): string
    {
        return VlidV2Factory::generate($dateTime, $lowerCase);
    }

    /**
     * Generate a VLID v3.
     *
     * @param DateTimeInterface|null $dateTime  [optional] The date time to use when generating the ULID
     * @param bool                   $lowerCase [optional] Whether to return as lower case
     *
     * @throws InvalidArgumentException
     * @throws RandomException
     * @throws RuntimeException
     */
    final public static function v3(DateTimeInterface|null $dateTime = null, bool $lowerCase = false): string
    {
        return VlidV3Factory::generate($dateTime, $lowerCase);
    }

    /**
     * Generate a VLID v4.
     *
     * @param DateTimeInterface|null $dateTime  [optional] The date time to use when generating the ULID
     * @param bool                   $lowerCase [optional] Whether to return as lower case
     *
     * @throws InvalidArgumentException
     * @throws RandomException
     * @throws RuntimeException
     */
    final public static function v4(DateTimeInterface|null $dateTime = null, bool $lowerCase = false): string
    {
        return VlidV4Factory::generate($dateTime, $lowerCase);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function getTimeFromMicroTime(string $time): string
    {
        return substr($time, 11) . substr($time, 2, 8);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function getTimeFromDateTime(DateTimeInterface $dateTime): string
    {
        return $dateTime->format('Uu');
    }

    /**
     * Format a time with random bytes.
     *
     * @param string $time The time
     */
    #[Override]
    protected static function formatTimeWithRandomBytes(string $time): string
    {
        return sprintf(static::FORMAT, $time, static::VERSION->value, ...static::getConvertedRandomBytesForFormat());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function areAllRandomBytesMax(): bool
    {
        return static::$randomBytes === [1 => self::MAX_PART, self::MAX_PART, self::MAX_PART];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function unsetRandomByteParts(array &$randomBytes): void
    {
        unset($randomBytes[4], $randomBytes[5]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidException("Invalid VLID $uid provided.");
    }
}
