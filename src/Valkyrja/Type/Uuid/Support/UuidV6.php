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
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Exception\InvalidUuidV6Exception;

use function str_replace;
use function substr;

/**
 * Class UuidV6.
 *
 * @author Melech Mizrachi
 */
class UuidV6 extends Uuid
{
    /** @var string */
    public const string REGEX = self::REGEX_PART . '{8}-'
        . self::REGEX_PART . '{4}-'
        . '[6]'
        . self::REGEX_PART . '{3}-'
        . self::REGEX_PART . '{4}-'
        . self::REGEX_PART . '{12}';

    /** @var Version */
    public const Version VERSION = Version::V6;

    /**
     * Generate a v6 UUID.
     *
     * @param string|null $node
     *
     * @throws Exception
     *
     * @return string
     */
    public static function generate(string|null $node = null): string
    {
        $uuid     = self::v1($node);
        $uuid     = str_replace('-', '', $uuid);
        $timeLow1 = substr($uuid, 0, 5);
        $timeLow2 = substr($uuid, 5, 3);
        $timeMid  = substr($uuid, 8, 4);
        $timeHigh = substr($uuid, 13, 3);
        $rest     = substr($uuid, 16);

        return $timeHigh . $timeMid . $timeLow1[0]
            . '-' . substr($timeLow1, 1)
            . '-6' . $timeLow2
            . '-' . substr($rest, 0, 4)
            . '-' . substr($rest, 4);
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidUuidV6Exception("Invalid UUID V6 $uid provided.");
    }
}
