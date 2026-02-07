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

namespace Valkyrja\Tests\Unit\Orm\Enum;

use Valkyrja\Orm\Enum\SortOrder;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class SortOrderTest extends TestCase
{
    public function testAscSortOrder(): void
    {
        self::assertSame('ASC', SortOrder::ASC->value);
    }

    public function testDescSortOrder(): void
    {
        self::assertSame('DESC', SortOrder::DESC->value);
    }

    public function testCasesReturnsAllSortOrders(): void
    {
        $cases = SortOrder::cases();

        self::assertCount(2, $cases);
        self::assertContains(SortOrder::ASC, $cases);
        self::assertContains(SortOrder::DESC, $cases);
    }
}
