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

namespace Valkyrja\Type\Uuid\Support;

use Exception;
use Override;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Exception\InvalidUuidV4Exception;

use function chr;
use function ord;

/**
 * Class UuidV4.
 *
 * @author Melech Mizrachi
 */
class UuidV4 extends Uuid
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
     * @throws Exception
     *
     * @return string
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
