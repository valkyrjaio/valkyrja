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
use Valkyrja\Orm\Entity\Trait\DatedFields;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class DatedFieldsTest extends TestCase
{
    public function testHasDateCreatedProperty(): void
    {
        $class = new class {
            use DatedFields;
        };

        $class->date_created = '01-26-2026 12:00:00 UTC';

        self::assertSame('01-26-2026 12:00:00 UTC', $class->date_created);
    }

    public function testHasDateModifiedProperty(): void
    {
        $class = new class {
            use DatedFields;
        };

        $class->date_modified = '01-26-2026 12:00:00 UTC';

        self::assertSame('01-26-2026 12:00:00 UTC', $class->date_modified);
    }

    public function testIncludesDateableTrait(): void
    {
        $class = new class {
            use DatedFields;
        };

        // Dateable methods should be available
        self::assertSame(DateFormat::DEFAULT, $class::getDateFormat());
        self::assertSame('date_created', $class::getDateCreatedField());
        self::assertSame('date_modified', $class::getDateModifiedField());
    }

    public function testGetFormattedDateIsAvailable(): void
    {
        $class = new class {
            use DatedFields;
        };

        $date = $class::getFormattedDate();

        self::assertIsString($date);
        self::assertNotEmpty($date);
    }
}
