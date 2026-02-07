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
use Valkyrja\Orm\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Classes\Orm\Support\DateFactoryWithFailingDateTimeClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

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

    public function testGetFormattedDateThrowsExceptionOnDateTimeFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failure occurred when creating a new DateTime object for current microtime.');

        DateFactoryWithFailingDateTimeClass::getFormattedDate();
    }
}
