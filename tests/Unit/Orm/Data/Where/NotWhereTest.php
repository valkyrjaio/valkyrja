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

namespace Valkyrja\Tests\Unit\Orm\Data\Where;

use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Data\Where\NotWhere;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class NotWhereTest extends TestCase
{
    public function testExtendsWhere(): void
    {
        $value = new Value('column', 'test');
        $where = new NotWhere($value);

        self::assertInstanceOf(Where::class, $where);
    }

    public function testHasNotType(): void
    {
        $value = new Value('column', 'test');
        $where = new NotWhere($value);

        self::assertSame(WhereType::NOT, $where->type);
    }

    public function testDefaultComparison(): void
    {
        $value = new Value('column', 'test');
        $where = new NotWhere($value);

        self::assertSame(Comparison::EQUALS, $where->comparison);
    }

    public function testCustomComparison(): void
    {
        $value = new Value('name', 'test');
        $where = new NotWhere($value, Comparison::LIKE);

        self::assertSame(Comparison::LIKE, $where->comparison);
    }

    public function testToString(): void
    {
        $value = new Value('status', 'active');
        $where = new NotWhere($value);

        self::assertSame('NOT = :status', (string) $where);
    }

    public function testPreservesValue(): void
    {
        $value = new Value('column', 'test');
        $where = new NotWhere($value);

        self::assertSame($value, $where->value);
    }

    public function testToStringWithDifferentComparison(): void
    {
        $value = new Value('name', 'test%');
        $where = new NotWhere($value, Comparison::LIKE);

        self::assertSame('NOT LIKE :name', (string) $where);
    }
}
