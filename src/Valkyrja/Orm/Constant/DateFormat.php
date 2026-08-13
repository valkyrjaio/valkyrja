<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Constant;

/**
 * The formats that the ORM writes to a date column.
 *
 * Each format puts the largest unit first, so a text sort of the column is a
 * date sort, and the date functions of the database read the value. Each
 * format also fits a column type: DEFAULT fits DATETIME, MILLISECOND fits
 * DATETIME(3), and MICROSECOND fits DATETIME(6).
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
