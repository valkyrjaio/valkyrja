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

use Valkyrja\Orm\Entity\Trait\SoftDeleteFields;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SoftDeleteFieldsTest extends TestCase
{
    public function testHasDateDeletedPropertyWithDefaultNull(): void
    {
        $class = new class {
            use SoftDeleteFields;
        };

        self::assertNull($class->date_deleted);
    }

    public function testDateDeletedCanBeSet(): void
    {
        $class = new class {
            use SoftDeleteFields;
        };

        $class->date_deleted = '01-26-2026 12:00:00 UTC';

        self::assertSame('01-26-2026 12:00:00 UTC', $class->date_deleted);
    }
}
