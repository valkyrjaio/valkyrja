<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Orm\Data\WhereGroup;

use ReflectionClass;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Data\Where\AndWhere;
use Valkyrja\Orm\Data\WhereGroup;
use Valkyrja\Orm\Data\WhereGroup\NotWhereGroup;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class NotWhereGroupTest extends TestCase
{
    public function testExtendsWhereGroup(): void
    {
        $whereGroup = new NotWhereGroup();

        self::assertInstanceOf(WhereGroup::class, $whereGroup);
    }

    public function testPreservesWhere(): void
    {
        $where1 = new Where(new Value('title', 'test'));
        $where2 = new AndWhere(new Value('body', 'test'));

        $whereGroup = new NotWhereGroup($where1, $where2);

        self::assertSame([$where1, $where2], $whereGroup->where);
    }

    public function testToStringWithEmptyGroup(): void
    {
        $whereGroup = new NotWhereGroup();

        self::assertSame('NOT ()', (string) $whereGroup);
    }

    public function testToStringWithSingleWhere(): void
    {
        $whereGroup = new NotWhereGroup(new Where(new Value('status', 'active')));

        self::assertSame('NOT (status = :status)', (string) $whereGroup);
    }

    public function testToStringWithMultipleWhere(): void
    {
        $whereGroup = new NotWhereGroup(
            new Where(new Value('title', '%orm%'), Comparison::LIKE),
            new AndWhere(new Value('body', '%orm%'), Comparison::LIKE),
        );

        self::assertSame('NOT (title LIKE :title AND body LIKE :body)', (string) $whereGroup);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(NotWhereGroup::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
