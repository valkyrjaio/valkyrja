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

namespace Valkyrja\Type;

use Valkyrja\Type\Enums\VlidVersion;
use Valkyrja\Type\Exceptions\InvalidVlidV2Exception;

/**
 * Class VlidV2.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier (VLID)
 * A more precise version of a ULID where time must be down to the microsecond, and  80 bits of randomness is required.
 *
 * @author Melech Mizrachi
 */
class VlidV2 extends Vlid
{
    public const REGEX = '[0-7]'
    . '[' . self::VALID_CHARACTERS . ']{12}'
    . '[2]'
    . '[' . self::VALID_CHARACTERS . ']{16}';

    public const VERSION = VlidVersion::V2;

    protected const FORMAT = '%013s%01s%04s%04s%04s%04s';

    protected const MAX_RANDOM_BYTES = 4;

    /**
     * @inheritDoc
     */
    protected static function areAllRandomBytesMax(): bool
    {
        return Ulid::areAllRandomBytesMax();
    }

    /**
     * @inheritDoc
     */
    protected static function unsetRandomByteParts(array &$randomBytes): void
    {
        Ulid::unsetRandomByteParts($randomBytes);
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidV2Exception("Invalid VLID V2 $uid provided.");
    }
}
