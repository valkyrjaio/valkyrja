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

namespace Valkyrja\Orm\Constant;

/**
 * The formats that the ORM writes to a date column.
 *
 * Each format is ISO 8601, so a text sort of the column is a date sort, and the
 * date functions of the database read the value. Each format also fits a column
 * type: DEFAULT fits DATETIME, MILLISECOND fits DATETIME(3), and MICROSECOND
 * fits DATETIME(6).
 *
 * No format holds a timezone. `DateFactory` builds each time in UTC, so a
 * timezone would render the same characters on every row. Read each stored
 * value as UTC.
 */
final class DateFormat
{
    public const string DEFAULT     = 'Y-m-d H:i:s';
    public const string MILLISECOND = 'Y-m-d H:i:s.v';
    public const string MICROSECOND = 'Y-m-d H:i:s.u';
}
