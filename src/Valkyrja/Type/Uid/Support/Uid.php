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

namespace Valkyrja\Type\Uid\Support;

use Valkyrja\Type\Ulid\Exception\InvalidUlidException;

/**
 * Abstract class Uid.
 *
 * @author Melech Mizrachi
 */
abstract class Uid
{
    /** @var string */
    public const string REGEX = '\w+';

    /**
     * Determine if a string is a valid UID.
     *
     * @param string $uid The UID to check
     *
     * @return bool
     */
    public static function isValid(string $uid): bool
    {
        return preg_match('/^' . static::REGEX . '$/i', $uid) === 1;
    }

    /**
     * Validate a UID.
     *
     * @param string $uid The UID to check
     *
     * @throws InvalidUlidException
     *
     * @return void
     */
    public static function validate(string $uid): void
    {
        if (! static::isValid($uid)) {
            static::throwInvalidException($uid);
        }
    }

    /**
     * Throw an invalid UID exception.
     *
     * @param string $uid The UID that failed a check
     *
     * @return never
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidUlidException("Invalid UID $uid provided.");
    }
}
