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

use Override;
use Random\RandomException;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV7Exception;

use function str_replace;
use function substr;

/**
 * Class UuidV7.
 *
 * @author Melech Mizrachi
 */
abstract class UuidV7 extends Uuid
{
    /** @var string */
    public const string REGEX = self::REGEX_PART . '{8}-'
        . self::REGEX_PART . '{4}-'
        . '[7]'
        . self::REGEX_PART . '{3}-'
        . self::REGEX_PART . '{4}-'
        . self::REGEX_PART . '{12}';

    /** @var Version */
    public const Version VERSION = Version::V7;

    /**
     * Generate a v7 UUID.
     *
     * @param string|null $node
     *
     * @throws RandomException
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
            . '-7' . $timeLow2
            . '-' . substr($rest, 0, 4)
            . '-' . substr($rest, 4);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidUuidV7Exception("Invalid UUID V7 $uid provided.");
    }
}
