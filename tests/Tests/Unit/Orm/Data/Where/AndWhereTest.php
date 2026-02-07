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
use Valkyrja\Orm\Data\Where\AndWhere;
use Valkyrja\Orm\Enum\Comparison;
use Valkyrja\Orm\Enum\WhereType;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class AndWhereTest extends TestCase
{
    public function testExtendsWhere(): void
    {
        $value = new Value('column', 'test');
        $where = new AndWhere($value);

        self::assertInstanceOf(Where::class, $where);
    }

    public function testHasAndType(): void
    {
        $value = new Value('column', 'test');
        $where = new AndWhere($value);

        self::assertSame(WhereType::AND, $where->type);
    }

    public function testDefaultComparison(): void
    {
        $value = new Value('column', 'test');
        $where = new AndWhere($value);

        self::assertSame(Comparison::EQUALS, $where->comparison);
    }

    public function testCustomComparison(): void
    {
        $value = new Value('age', 18);
        $where = new AndWhere($value, Comparison::GREATER_THAN);

        self::assertSame(Comparison::GREATER_THAN, $where->comparison);
    }

    public function testToString(): void
    {
        $value = new Value('status', 'active');
        $where = new AndWhere($value);

        self::assertSame('AND = :status', (string) $where);
    }

    public function testPreservesValue(): void
    {
        $value = new Value('column', 'test');
        $where = new AndWhere($value);

        self::assertSame($value, $where->value);
    }

    public function testToStringWithDifferentComparison(): void
    {
        $value = new Value('price', 100);
        $where = new AndWhere($value, Comparison::GREATER_THAN);

        self::assertSame('AND > :price', (string) $where);
    }
}
