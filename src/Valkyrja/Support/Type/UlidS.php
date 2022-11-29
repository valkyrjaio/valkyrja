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

use Valkyrja\Support\Type\Exceptions\InvalidShortUlidException;

/**
 * Class UlidS.
 *
 * A shortened version of a ULID where less random bytes is acceptable.
 *
 * @author Melech Mizrachi
 */
class UlidS extends Ulid
{
    public const REGEX = '[0-7][' . self::VALID_CHARACTERS . ']{17}';

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
    protected static function formatTimeWithRandomBytes(string $time): string
    {
        return sprintf(
            '%010s%04s%04s',
            $time,
            static::convertRandomBytesPart(1),
            static::convertRandomBytesPart(2),
        );
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidShortUlidException("Invalid short ULID $uid provided.");
    }
}
