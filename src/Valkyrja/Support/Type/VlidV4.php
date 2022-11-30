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

use Valkyrja\Support\Type\Enums\VlidVersion;
use Valkyrja\Support\Type\Exceptions\InvalidVlidV4Exception;

/**
 * Class VlidV4.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier (VLID)
 * A more precise version of a ULID where time must be down to the microsecond, and  80 bits of randomness is required.
 * A tiny version of a VLID where much less random bytes is acceptable.
 *
 * @author Melech Mizrachi
 */
class VlidV4 extends Vlid
{
    public const REGEX = '[0-7][' . self::VALID_CHARACTERS . ']{12}[4][' . self::VALID_CHARACTERS . ']{4}';

    public const VERSION = VlidVersion::V4;

    protected const FORMAT = '%013s%01s%04s';

    protected const MAX_RANDOM_BYTES = 1;

    /**
     * @inheritDoc
     */
    protected static function areAllRandomBytesMax(): bool
    {
        return static::$randomBytes === [1 => self::MAX_PART];
    }

    /**
     * @inheritDoc
     */
    protected static function unsetRandomByteParts(array &$randomBytes): void
    {
        unset($randomBytes[2], $randomBytes[3], $randomBytes[4], $randomBytes[5]);
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidV4Exception("Invalid VLID V4 $uid provided.");
    }
}
