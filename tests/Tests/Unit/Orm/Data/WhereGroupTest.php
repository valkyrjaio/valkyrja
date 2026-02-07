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
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Data\WhereGroup;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class WhereGroupTest extends TestCase
{
    public function testImplementsStringable(): void
    {
        $whereGroup = new WhereGroup();

        self::assertInstanceOf(Stringable::class, $whereGroup);
    }

    public function testWherePropertyWithNoArguments(): void
    {
        $whereGroup = new WhereGroup();

        self::assertSame([], $whereGroup->where);
    }

    public function testWherePropertyWithSingleWhere(): void
    {
        $value = new Value('column', 'test');
        $where = new Where($value);

        $whereGroup = new WhereGroup($where);

        self::assertCount(1, $whereGroup->where);
        self::assertSame($where, $whereGroup->where[0]);
    }

    public function testWherePropertyWithMultipleWhere(): void
    {
        $value1 = new Value('column1', 'test1');
        $where1 = new Where($value1);

        $value2 = new Value('column2', 'test2');
        $where2 = new Where($value2, Comparison::EQUALS, WhereType::AND);

        $whereGroup = new WhereGroup($where1, $where2);

        self::assertCount(2, $whereGroup->where);
        self::assertSame($where1, $whereGroup->where[0]);
        self::assertSame($where2, $whereGroup->where[1]);
    }

    public function testToStringWithEmptyGroup(): void
    {
        $whereGroup = new WhereGroup();

        self::assertSame('()', (string) $whereGroup);
    }

    public function testToStringWithSingleWhere(): void
    {
        $value = new Value('status', 'active');
        $where = new Where($value);

        $whereGroup = new WhereGroup($where);

        self::assertSame('( = :status)', (string) $whereGroup);
    }

    public function testToStringWithMultipleWhere(): void
    {
        $value1 = new Value('status', 'active');
        $where1 = new Where($value1);

        $value2 = new Value('role', 'admin');
        $where2 = new Where($value2, Comparison::EQUALS, WhereType::OR);

        $whereGroup = new WhereGroup($where1, $where2);

        $result = (string) $whereGroup;

        self::assertStringStartsWith('(', $result);
        self::assertStringEndsWith(')', $result);
        self::assertStringContainsString(':status', $result);
        self::assertStringContainsString(':role', $result);
        self::assertStringContainsString('OR', $result);
    }

    public function testReadonlyClass(): void
    {
        $reflection = new ReflectionClass(WhereGroup::class);

        self::assertTrue($reflection->isReadOnly());
    }
}
