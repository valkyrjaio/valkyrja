<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
