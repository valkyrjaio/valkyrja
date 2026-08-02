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

namespace Valkyrja\Orm\Factory;

use DateTime;
use DateTimeZone;
use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Throwable\Exception\OrmDateException;

class DateFactory
{
    /**
     * Get the formatted date.
     *
     * @param string $format [optional] The format
     */
    public static function getFormattedDate(string $format = DateFormat::DEFAULT): string
    {
        $dateTime = static::createDateTimeFromMicrotime();

        if ($dateTime === false) {
            throw new OrmDateException('Failure occurred when creating a new DateTime object for current microtime.');
        }

        return $dateTime->format($format);
    }

    /**
     * Create a DateTime for the current time in UTC.
     *
     * The constructor reads the system clock directly, which keeps the
     * microseconds that the clock reports. Do not route the time through a
     * float: `(string) microtime(true)` keeps only four decimal places, and it
     * drops the decimal point when the time lands on a whole second, which
     * makes the value unparsable.
     *
     * Warning: the timezone argument is required. The constructor uses the
     * default timezone of the application when the argument is absent, which
     * stamps a local time instead of UTC. The offset `+00:00` keeps the same
     * rendering that this factory gave before.
     *
     * @return DateTime|false
     */
    protected static function createDateTimeFromMicrotime(): DateTime|false
    {
        return new DateTime('now', new DateTimeZone('+00:00'));
    }
}
