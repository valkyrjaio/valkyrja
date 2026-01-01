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

namespace Valkyrja\Type\Vlid\Support;

use Override;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV4Exception;

/**
 * Class VlidV4.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier (VLID)
 * A more precise version of a ULID where time must be down to the microsecond, and  80 bits of randomness is required.
 * A tiny version of a VLID where much less random bytes is acceptable.
 */
class VlidV4 extends Vlid
{
    /** @var string */
    public const string REGEX = '[0-7]'
        . '[' . self::VALID_CHARACTERS . ']{12}'
        . '[4]'
        . '[' . self::VALID_CHARACTERS . ']{4}';

    /** @var Version */
    public const Version VERSION = Version::V4;

    /** @var string */
    protected const string FORMAT = '%013s%01s%04s';

    /** @var int */
    protected const int MAX_RANDOM_BYTES = 1;

    /** @var string */
    protected static string $time = '';

    /** @var array<int, int> */
    protected static array $randomBytes = [];

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function areAllRandomBytesMax(): bool
    {
        return static::$randomBytes === [1 => self::MAX_PART];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function unsetRandomByteParts(array &$randomBytes): void
    {
        unset($randomBytes[2], $randomBytes[3], $randomBytes[4], $randomBytes[5]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidV4Exception("Invalid VLID V4 $uid provided.");
    }
}
