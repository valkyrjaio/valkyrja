<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Factory;

use DateTime;
use DateTimeZone;
use Valkyrja\Orm\Constant\DateFormat;

class DateFactory
{
    /**
     * Get the formatted date.
     *
     * @param string $format [optional] The format
     */
    public static function getFormattedDate(string $format = DateFormat::DEFAULT): string
    {
        return static::createDateTimeFromMicrotime()->format($format);
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
     * The method returns a DateTime and never a failure value. The constructor
     * throws for a bad argument, and both arguments here are literals.
     */
    protected static function createDateTimeFromMicrotime(): DateTime
    {
        return new DateTime('now', new DateTimeZone('+00:00'));
    }
}
