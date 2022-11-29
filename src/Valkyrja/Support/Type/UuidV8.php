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

use Exception;
use Valkyrja\Support\Type\Enums\UuidVersion;
use Valkyrja\Support\Type\Exceptions\InvalidUuidV8Exception;

/**
 * Class UuidV8.
 *
 * @author Melech Mizrachi
 */
abstract class UuidV8 extends Uuid
{
    public const REGEX = self::REGEX_PART . '{8}-'
    . self::REGEX_PART . '{4}-'
    . '[8]'
    . self::REGEX_PART . '{3}-'
    . self::REGEX_PART . '{4}-'
    . self::REGEX_PART . '{12}';

    protected const VERSION = UuidVersion::V8;

    /**
     * Generate a v8 UUID.
     *
     * @throws Exception
     *
     * @return string
     */
    abstract public static function generate(): string;

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidUuidV8Exception("Invalid UUID V8 $uid provided.");
    }
}
