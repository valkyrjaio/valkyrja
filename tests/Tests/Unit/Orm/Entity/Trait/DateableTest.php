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
use Valkyrja\Orm\Entity\Trait\Dateable;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class DateableTest extends TestCase
{
    public function testGetDateFormatReturnsDefaultFormat(): void
    {
        $class = new class {
            use Dateable;
        };

        self::assertSame(DateFormat::DEFAULT, $class::getDateFormat());
    }

    public function testGetFormattedDateReturnsFormattedString(): void
    {
        $class = new class {
            use Dateable;
        };

        $date = $class::getFormattedDate();

        self::assertIsString($date);
        self::assertNotEmpty($date);
    }

    public function testGetDateCreatedFieldReturnsDateCreated(): void
    {
        $class = new class {
            use Dateable;
        };

        self::assertSame('date_created', $class::getDateCreatedField());
    }

    public function testGetDateModifiedFieldReturnsDateModified(): void
    {
        $class = new class {
            use Dateable;
        };

        self::assertSame('date_modified', $class::getDateModifiedField());
    }

    public function testGetFormattedDateMatchesExpectedFormat(): void
    {
        $class = new class {
            use Dateable;
        };

        $date = $class::getFormattedDate();

        // Default format: 'm-d-Y H:i:s T'
        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}/', $date);
    }
}
