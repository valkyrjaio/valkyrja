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

namespace Valkyrja\Tests\Unit\Orm\Support;

use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Support\Helpers;
use Valkyrja\Orm\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Classes\Orm\Support\HelpersWithFailingDateTimeClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class HelpersTest extends TestCase
{
    public function testGetColumnForValueBindSimpleColumn(): void
    {
        $result = Helpers::getColumnForValueBind('column');

        self::assertSame(':column', $result);
    }

    public function testGetColumnForValueBindRemovesDots(): void
    {
        $result = Helpers::getColumnForValueBind('table.column');

        self::assertSame(':tablecolumn', $result);
    }

    public function testGetColumnForValueBindRemovesColons(): void
    {
        $result = Helpers::getColumnForValueBind(':column');

        self::assertSame(':column', $result);
    }

    public function testGetColumnForValueBindRemovesDashes(): void
    {
        $result = Helpers::getColumnForValueBind('some-column');

        self::assertSame(':somecolumn', $result);
    }

    public function testGetColumnForValueBindRemovesMultipleSpecialChars(): void
    {
        $result = Helpers::getColumnForValueBind('table.some-column:value');

        self::assertSame(':tablesomecolumnvalue', $result);
    }

    public function testGetFormattedDateReturnsString(): void
    {
        $result = Helpers::getFormattedDate();

        self::assertIsString($result);
        self::assertNotEmpty($result);
    }

    public function testGetFormattedDateWithDefaultFormat(): void
    {
        $result = Helpers::getFormattedDate();

        // Default format: 'm-d-Y H:i:s T'
        // Should match pattern like: 01-26-2026 12:30:45 UTC
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2} [A-Z]{3,}\+\d{4}$/', $result);
    }

    public function testGetFormattedDateWithMillisecondFormat(): void
    {
        $result = Helpers::getFormattedDate(DateFormat::MILLISECOND);

        // Millisecond format: 'm-d-Y H:i:s.v T'
        // Should contain milliseconds
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}\.\d{3} [A-Z]{3,}\+\d{4}$/', $result);
    }

    public function testGetFormattedDateWithMicrosecondFormat(): void
    {
        $result = Helpers::getFormattedDate(DateFormat::MICROSECOND);

        // Microsecond format: 'm-d-Y H:i:s.u T'
        // Should contain microseconds
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}\.\d{6} [A-Z]{3,}\+\d{4}$/', $result);
    }

    public function testGetFormattedDateWithCustomFormat(): void
    {
        $result = Helpers::getFormattedDate('Y-m-d');

        // Should match YYYY-MM-DD format
        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result);
    }

    public function testGetFormattedDateThrowsExceptionOnDateTimeFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failure occurred when creating a new DateTime object for current microtime.');

        HelpersWithFailingDateTimeClass::getFormattedDate();
    }
}
