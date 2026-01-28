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
use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;

use function microtime;

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
            throw new RuntimeException('Failure occurred when creating a new DateTime object for current microtime.');
        }

        return $dateTime->format($format);
    }

    /**
     * Create a DateTime from the current microtime.
     *
     * @return DateTime|false
     */
    protected static function createDateTimeFromMicrotime(): DateTime|false
    {
        return DateTime::createFromFormat('U.u', (string) microtime(true));
    }
}
