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

namespace Valkyrja\Tests\Unit\Orm\Data;

use ReflectionClass;
use Stringable;
use Valkyrja\Orm\Data\OrderBy;
use Valkyrja\Orm\Enum\SortOrder;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class OrderByTest extends TestCase
{
    public function testImplementsStringable(): void
    {
        $orderBy = new OrderBy('created_at');

        self::assertInstanceOf(Stringable::class, $orderBy);
    }

    public function testFieldProperty(): void
    {
        $orderBy = new OrderBy('created_at');

        self::assertSame('created_at', $orderBy->field);
    }

    public function testDefaultSortOrderIsAsc(): void
    {
        $orderBy = new OrderBy('created_at');

        self::assertSame(SortOrder::ASC, $orderBy->order);
    }

    public function testCustomSortOrder(): void
    {
        $orderBy = new OrderBy('created_at', SortOrder::DESC);

        self::assertSame(SortOrder::DESC, $orderBy->order);
    }

    public function testToStringWithAsc(): void
    {
        $orderBy = new OrderBy('created_at');

        self::assertSame('created_at ASC', (string) $orderBy);
    }

    public function testToStringWithDesc(): void
    {
        $orderBy = new OrderBy('created_at', SortOrder::DESC);

        self::assertSame('created_at DESC', (string) $orderBy);
    }

    public function testToStringWithDifferentField(): void
    {
        $orderBy = new OrderBy('users.name', SortOrder::ASC);

        self::assertSame('users.name ASC', (string) $orderBy);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(OrderBy::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
