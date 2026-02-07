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

namespace Valkyrja\Tests\Unit\Orm\Constant;

use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class DateFormatTest extends TestCase
{
    public function testDefaultFormat(): void
    {
        self::assertSame('m-d-Y H:i:s T', DateFormat::DEFAULT);
    }

    public function testMillisecondFormat(): void
    {
        self::assertSame('m-d-Y H:i:s.v T', DateFormat::MILLISECOND);
    }

    public function testMicrosecondFormat(): void
    {
        self::assertSame('m-d-Y H:i:s.u T', DateFormat::MICROSECOND);
    }

    public function testFormatsContainTimezone(): void
    {
        self::assertStringContainsString('T', DateFormat::DEFAULT);
        self::assertStringContainsString('T', DateFormat::MILLISECOND);
        self::assertStringContainsString('T', DateFormat::MICROSECOND);
    }

    public function testFormatsContainTime(): void
    {
        self::assertStringContainsString('H:i:s', DateFormat::DEFAULT);
        self::assertStringContainsString('H:i:s', DateFormat::MILLISECOND);
        self::assertStringContainsString('H:i:s', DateFormat::MICROSECOND);
    }
}
