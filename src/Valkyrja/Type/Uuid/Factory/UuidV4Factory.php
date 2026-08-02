<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Uuid\Factory;

use Override;
use Random\RandomException;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV4Exception;

use function bin2hex;
use function chr;
use function ord;
use function random_bytes;
use function str_split;
use function vsprintf;

class UuidV4Factory extends UuidFactory
{
    /** @var string */
    public const string REGEX = self::REGEX_PART . '{8}-'
        . self::REGEX_PART . '{4}-'
        . '[4]'
        . self::REGEX_PART . '{3}-'
        . self::REGEX_PART . '{4}-'
        . self::REGEX_PART . '{12}';

    /** @var Version */
    public const Version VERSION = Version::V4;

    /**
     * Generate a v4 UUID.
     *
     * @throws RandomException
     */
    public static function generate(): string
    {
        $data = random_bytes(16);
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidUuidV4Exception("Invalid UUID V4 $uid provided.");
    }
}
