<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Factory;

use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Factory\DateFactory;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function array_filter;
use function date_default_timezone_get;
use function date_default_timezone_set;
use function substr;

final class DateFactoryTest extends TestCase
{
    public function testGetFormattedDateReturnsString(): void
    {
        $result = DateFactory::getFormattedDate();

        self::assertNotEmpty($result);
    }

    public function testGetFormattedDateWithDefaultFormat(): void
    {
        $result = DateFactory::getFormattedDate();

        // Default format: 'Y-m-d H:i:s'
        // Should match a pattern such as: 2026-01-26 12:30:45
        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result);
    }

    public function testGetFormattedDateWithMillisecondFormat(): void
    {
        $result = DateFactory::getFormattedDate(DateFormat::MILLISECOND);

        // Millisecond format: 'Y-m-d H:i:s.v'
        // Should contain milliseconds
        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{3}$/', $result);
    }

    public function testGetFormattedDateWithMicrosecondFormat(): void
    {
        $result = DateFactory::getFormattedDate(DateFormat::MICROSECOND);

        // Microsecond format: 'Y-m-d H:i:s.u'
        // Should contain microseconds
        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{6}$/', $result);
    }

    public function testGetFormattedDateWithCustomFormat(): void
    {
        $result = DateFactory::getFormattedDate('Y-m-d');

        // Should match YYYY-MM-DD format
        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result);
    }

    /**
     * The factory read the clock through `(string) microtime(true)` before.
     * That cast keeps four decimal places, so the clock resolved to 100
     * microseconds and the last two digits were always zero. The cast also
     * dropped the decimal point when the clock landed on a whole second, which
     * made `U.u` fail to parse and made the factory throw for about one call in
     * one hundred thousand.
     *
     * Both defects have the same cause, so this test guards both: a sample that
     * carries a non-zero digit in the last two places proves that the factory
     * does not read the clock through a float.
     */
    public function testMicrosecondsHoldRealPrecision(): void
    {
        $samples = [];

        for ($i = 0; $i < 25; $i++) {
            $samples[] = substr(DateFactory::getFormattedDate('u'), -2);
        }

        self::assertNotEmpty(
            array_filter($samples, static fn (string $sample): bool => $sample !== '00'),
            'Every sample ends in two zeros, so the factory reads the clock through a float.'
        );
    }

    /**
     * `createFromFormat('U.u', ...)` forced UTC. A plain `new DateTime()` takes
     * the default timezone of the application instead, which stamps a local
     * time. The factory must stamp UTC whatever the default timezone is.
     */
    public function testTheDateIsUtcWhenTheDefaultTimezoneIsNot(): void
    {
        $original = date_default_timezone_get();

        date_default_timezone_set('America/New_York');

        try {
            self::assertSame('+00:00', DateFactory::getFormattedDate('P'));
        } finally {
            date_default_timezone_set($original);
        }
    }
}
