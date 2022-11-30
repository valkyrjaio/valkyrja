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

use DateTimeInterface;
use Valkyrja\Support\Type\Exceptions\InvalidVlidException;

/**
 * Class Vlid.
 *
 * Valkyrja Universally Unique Lexicographically Sortable Identifier (VLID)
 * A more precise version of a ULID where time must be down to the microsecond.
 *
 * @author Melech Mizrachi
 */
class Vlid extends Ulid
{
    public const REGEX = '[0-7][' . self::VALID_CHARACTERS . ']{28}';

    protected const MAX_RANDOM_BYTES = 4;

    protected const FORMAT = '%013s%04s%04s%04s%04s';

    /**
     * Get the time from micro time.
     *
     * @param string $time The micro time
     *
     * @return string
     */
    protected static function getTimeFromMicroTime(string $time): string
    {
        return substr($time, 11) . substr($time, 2, 8);
    }

    /**
     * Get the time from a datetime.
     *
     * @param DateTimeInterface $dateTime The date time to use when generating the ULID
     *
     * @return string
     */
    protected static function getTimeFromDateTime(DateTimeInterface $dateTime): string
    {
        return $dateTime->format('Uu');
    }

    /**
     * @inheritDoc
     */
    protected static function throwInvalidException(string $uid): never
    {
        throw new InvalidVlidException("Invalid VLID $uid provided.");
    }
}
