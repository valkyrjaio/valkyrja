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

use Valkyrja\Support\Type\Exceptions\InvalidTinyUlidException;

/**
 * Class UlidT.
 *
 * A tiny version of a ULID where much less random bytes is acceptable.
 *
 * @author Melech Mizrachi
 */
class UlidT extends Ulid
{
    public const REGEX = '[0-7][' . self::VALID_CHARACTERS . ']{13}';

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
    protected static function formatTimeWithRandomBytes(string $time): string
    {
        return sprintf(
            '%010s%04s',
            $time,
            static::convertRandomBytesPart(1),
        );
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidTinyUlidException("Invalid tiny ULID $uid provided.");
    }
}
