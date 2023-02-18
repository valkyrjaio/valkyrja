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

namespace Valkyrja\Type\Support;

use Valkyrja\Type\Enums\VlidVersion;
use Valkyrja\Type\Exceptions\InvalidVlidV3Exception;

/**
 * Class VlidV3.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier (VLID)
 * A more precise version of a ULID where time must be down to the microsecond, and  80 bits of randomness is required.
 * A shortened version of a VLID where less random bytes is acceptable.
 *
 * @author Melech Mizrachi
 */
class VlidV3 extends Vlid
{
    public const REGEX = '[0-7]'
    . '[' . self::VALID_CHARACTERS . ']{12}'
    . '[3]'
    . '[' . self::VALID_CHARACTERS . ']{8}';

    public const VERSION = VlidVersion::V3;

    protected const FORMAT = '%013s%01s%04s%04s';

    protected const MAX_RANDOM_BYTES = 2;

    /**
     * @inheritDoc
     */
    protected static function areAllRandomBytesMax(): bool
    {
        return static::$randomBytes === [1 => self::MAX_PART, self::MAX_PART];
    }

    /**
     * @inheritDoc
     */
    protected static function unsetRandomByteParts(array &$randomBytes): void
    {
        unset($randomBytes[3], $randomBytes[4], $randomBytes[5]);
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidV3Exception("Invalid VLID V3 $uid provided.");
    }
}
