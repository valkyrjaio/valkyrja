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

namespace Valkyrja\Tests\Unit\Orm\Entity\Trait;

use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Entity\Trait\SoftDeletable;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class SoftDeletableTest extends TestCase
{
    public function testGetDeletedDateFormatReturnsDefaultFormat(): void
    {
        $class = new class {
            use SoftDeletable;
        };

        self::assertSame(DateFormat::DEFAULT, $class::getDeletedDateFormat());
    }

    public function testGetFormattedDeletedDateReturnsFormattedString(): void
    {
        $class = new class {
            use SoftDeletable;
        };

        $date = $class::getFormattedDeletedDate();

        self::assertIsString($date);
        self::assertNotEmpty($date);
    }

    public function testGetDateDeletedFieldReturnsDateDeleted(): void
    {
        $class = new class {
            use SoftDeletable;
        };

        self::assertSame('date_deleted', $class::getDateDeletedField());
    }

    public function testGetFormattedDeletedDateMatchesExpectedFormat(): void
    {
        $class = new class {
            use SoftDeletable;
        };

        $date = $class::getFormattedDeletedDate();

        // Default format: 'm-d-Y H:i:s T'
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}/', $date);
    }
}
