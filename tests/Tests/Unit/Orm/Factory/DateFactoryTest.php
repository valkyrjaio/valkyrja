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

        self::assertIsString($result);
        self::assertNotEmpty($result);
    }

    public function testGetFormattedDateWithDefaultFormat(): void
    {
        $result = DateFactory::getFormattedDate();

        // Default format: 'm-d-Y H:i:s T'
        // Should match pattern like: 01-26-2026 12:30:45 UTC
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2} [A-Z]{3,}\+\d{4}$/', $result);
    }

    public function testGetFormattedDateWithMillisecondFormat(): void
    {
        $result = DateFactory::getFormattedDate(DateFormat::MILLISECOND);

        // Millisecond format: 'm-d-Y H:i:s.v T'
        // Should contain milliseconds
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}\.\d{3} [A-Z]{3,}\+\d{4}$/', $result);
    }

    public function testGetFormattedDateWithMicrosecondFormat(): void
    {
        $result = DateFactory::getFormattedDate(DateFormat::MICROSECOND);

        // Microsecond format: 'm-d-Y H:i:s.u T'
        // Should contain microseconds
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}\.\d{6} [A-Z]{3,}\+\d{4}$/', $result);
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
