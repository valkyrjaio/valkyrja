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

use Valkyrja\Support\Type\Exceptions\InvalidVlidMediumException;

/**
 * Class VlidM.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier - Medium (VLID-M)
 * A more precise version of a ULID where time must be down to the microsecond.
 * A medium version of a VLID where some less random bytes is acceptable.
 *
 * @author Melech Mizrachi
 */
class VlidM extends Vlid
{
    public const REGEX = '[0-7][' . self::VALID_CHARACTERS . ']{24}';

    protected const MAX_RANDOM_BYTES = 3;

    protected const FORMAT = '%013s%04s%04s%04s';

    /**
     * @inheritDoc
     */
    protected static function areAllRandomBytesMax(): bool
    {
        return static::$randomBytes === [1 => self::MAX_PART, self::MAX_PART, self::MAX_PART];
    }

    /**
     * @inheritDoc
     */
    protected static function unsetRandomByteParts(array &$randomBytes): void
    {
        unset($randomBytes[4], $randomBytes[5]);
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidMediumException("Invalid VLID-M $uid provided.");
    }
}
