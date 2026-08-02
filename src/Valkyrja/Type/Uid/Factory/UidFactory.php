<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Uid\Factory;

use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;

use function preg_match;

class UidFactory
{
    /** @var string */
    public const string REGEX = '\w+';

    /**
     * Determine if a string is a valid UID.
     *
     * @param string $uid The UID to check
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
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidUlidException("Invalid UID $uid provided.");
    }
}
