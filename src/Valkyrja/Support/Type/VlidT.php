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

use Valkyrja\Support\Type\Exceptions\InvalidVlidTinyException;

/**
 * Class VlidT.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier - Tiny (VLID-T)
 * A more precise version of a ULID where time must be down to the microsecond.
 * A tiny version of a VLID where much less random bytes is acceptable.
 *
 * @author Melech Mizrachi
 */
class VlidT extends Vlid
{
    public const REGEX = '[0-7][' . self::VALID_CHARACTERS . ']{16}';

    protected const MAX_RANDOM_BYTES = 1;

    protected const FORMAT = '%013s%04s';

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
        throw new InvalidVlidTinyException("Invalid VLID-T $uid provided.");
    }
}
